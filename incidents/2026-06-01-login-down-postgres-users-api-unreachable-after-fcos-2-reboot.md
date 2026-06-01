# 2026-06-01: login (foreningenbs.no) down — postgresql-1 / users-api unreachable across ZeroTier after fcos-2 reboot

## Timeline

- **2026-05-31 23:53:09 UTC** — last green scheduled `e2e-tests` run.
- **2026-06-01 00:04:06 UTC** — fcos-2 rebooted (Fedora CoreOS / Zincati automatic OS update; previous boot 2026-05-18 22:14). All fcos-2 containers recreated at 00:04:30 with fresh veths/MACs; three came up stranded.
- **2026-06-01 01:33:40 UTC** — first failing `e2e-tests` run (first scheduled run after the reboot). Failing hourly thereafter; failing runs took ~21m (retries/timeouts) vs ~3m green.
- **2026-06-01 ~13:00 UTC** — user reported "unable to login in simplesaml, e2e failing"; investigation started.
- **2026-06-01 13:38 UTC** — `docker restart postgresql-1 users-api slack-invite-automation` on fcos-2. All three reachable cross-host immediately.
- **2026-06-01 13:41 UTC** — `intern.spec.ts` login + `api.spec.ts` matmeny pass against prod.

## Impact

- `foreningenbs.no` login (SAML) broken for ~13.5h: the intern "Logg inn" route queries Postgres, so it 500'd before redirecting to the IdP — the SSP login page never appeared. (SimpleSAMLphp and OpenLDAP themselves were healthy.)
- matmeny API returned 500 (`write CONNECT_TIMEOUT postgresql-1.zt.foreningenbs.no:5432`).
- `webdavcgi` (`/filer/`) login failed (same intern/SAML path).
- `users-api` (auth/user lookups) and `slack-invite-automation` unreachable across hosts.
- Hourly e2e failing continuously from 01:33 UTC.

## Root cause

Recurrence of the ZeroTier bridge MAC-stranding pattern — see `2026-03-21-confluence-down.md`, `2026-04-26-uka-billett-down.md`, `2026-05-02-filer-503-after-fcos-3-reboot.md`, `2026-05-02-otel-collector-deploy-fbshs1-unreachable.md`.

- **Trigger (this time):** the FCOS auto-reboot recreated all fcos-2 containers simultaneously. Three of them — `postgresql-1` (172.25.16.42), `users-api` (172.25.16.2), `slack-invite-automation` (172.25.16.9) — came up stranded; the rest (`mysql-1`, `ldap-master`, `gatus`, `okoreports-*`, `snipeit`, …) came up fine.
- **Underlying condition:** containers attach to the `fbs0` Docker bridge, which is L2-bridged to ZeroTier via `ztfbs0`. For a container to be reachable cross-host, peer ZeroTier nodes must hold a bridge-route mapping its MAC to the owner node. ZeroTier does not flood unknown unicast, so a missing route silently black-holes all unicast to that MAC. After a mass recreate, some MACs never get that route installed on peers, and it does not self-heal.

Evidence gathered this time (which the prior reports left as "mechanism not confirmed"):

- On fcos-2, the local bridge state for a stranded container was **identical to a working one**: MAC present in `bridge fdb` on its veth, veth + `ztfbs0` `master fbs0 state forwarding`. Postgres was reachable on `5432` from fcos-2 itself.
- The peer's cached ARP entry for the stranded container held the **correct current MAC** (`22:86:f6:1d:6e:03`) and was even marked `REACHABLE` — yet TCP timed out. ICMP was intermittently "OK". So ARP/ping are false-green signals.
- `tcpdump -i ztfbs0` on fcos-2 while a peer probed:
  - postgres's own gratuitous ARP **egressed** onto ZeroTier.
  - A fresh broadcast ARP from the peer **reached** fcos-2; postgres's **unicast reply reached the peer** (peer marked `REACHABLE`).
  - But the peer's subsequent ICMP/TCP unicast to postgres's MAC **never arrived** on fcos-2's `ztfbs0`. The black hole is peer→stranded-MAC unicast only; broadcast and owner→peer unicast both work. It is **per-MAC**, not per-node (mysql, behind the same fcos-2 ZeroTier node, worked throughout).
- `zerotier-cli` on every node reported healthy: fcos-2↔fcos-3 on a **DIRECT** path, `ONLINE`, controller reachable. `zerotier-cli dump` contains no learned bridge-MAC table. **ZeroTier exposes no signal for this fault.**
- Owner-side repair levers tried, in order, and their effect on the stranded MACs:
  - gratuitous ARP from the container — **no effect**.
  - `ztfbs0` detach/reattach from `fbs0` — **no effect**.
  - `systemctl restart zerotier-one` on fcos-2 — re-advertised everything and restored `mysql-1`, but the **stranded MACs stayed dead**.
  - `docker restart <container>` — **fixed it** (fresh veth).

Conclusion: only recreating the container's veth (`docker restart`) reliably clears the stranding. No owner-side network action repairs an already-stranded MAC.

## Resolution

```sh
ssh root@fcos-2.nrec.foreningenbs.no docker restart postgresql-1 users-api slack-invite-automation
```

Verify cross-host (from a host that is **not** the owner — local probes always succeed), using each container's real port:

```sh
# postgresql-1:5432, users-api:8000, slack-invite-automation:3000
for hp in 172.25.16.42:5432 172.25.16.2:8000 172.25.16.9:3000; do
  timeout 4 bash -c "</dev/tcp/${hp%:*}/${hp#*:}" && echo "$hp OPEN" || echo "$hp TIMEOUT"
done
```

## Observations

- **Do not use `docker restart zerotier-one`.** The container is launched by the `zerotier-one.service` systemd unit, whose `ExecStartPost` re-enslaves `ztfbs0` to `fbs0`. A plain `docker restart` recreates `ztfbs0` **without** that step, leaving it un-enslaved and breaking *all* fcos-2 cross-host traffic. Use `systemctl restart zerotier-one` (it ran the reattach and restored `master fbs0`).
- **Detection requires a TCP probe from a non-owner host.** Ping, ARP, and `zerotier-cli` all report green during this fault. The probe must use the real service port (TIMEOUT = stranded; refused/CLOSED = reachable-but-not-listening).
- **Gatus runs on fcos-2 and was blind to this** — it checks fcos-2's own containers locally, where they are always reachable. A monitor co-located with the service it checks cannot see this fault.
- SimpleSAMLphp's inline OTEL trace export (`OTEL_EXPORTER_OTLP_ENDPOINT=…signoz-otel-collector…:4318`) has been failing since 2026-05-30 (SigNoz/fbshs1 path) and logs a stack trace per request at PHP shutdown. It is post-response (page renders in ~40ms) and was **not** related to this outage — but it is noisy and worth decoupling (export to the local otel-collector-agent, or fail-fast).

## Follow-ups

- Automatic detection + recovery: `ansible/roles/zt-bridge-watchdog/` — a per-host timer that checks each local container from inside its own network namespace (a stranded container reaches no peer host) and `docker restart`s it locally. No cross-host SSH; auto-restarts only a minority of containers (a host-wide failure alerts instead). Closes the gap the prior four reports all recommended ("cross-host container connectivity health check") but never built.
- Consider eliminating the trigger: re-establish clean bridging after boot/recreate, or order container attach after `ztfbs0` is stably enslaved.
