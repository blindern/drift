# 2026-05-02: otel-collector workflow Deploy step fails — fbshs1 unreachable from deployer container

## Timeline

- **2026-05-01 01:00:28 UTC** — fcos-3 rebooted by Zincati / `rpm-ostreed` (also the trigger in `2026-05-02-filer-503-after-fcos-3-reboot.md`).
- **2026-05-02 17:58–17:59 UTC** — `otel-collector` workflow run `25258140723` Deploy step fails. Deployer (on fcos-3) runs `ansible-playbook -t otel-collector-agent` against all hosts; only fbshs1 is unreachable:

  ```
  [ERROR]: Task failed: Data could not be sent to remote host "fbshs1.zt.foreningenbs.no".
  Make sure this host can be reached over ssh: ssh: connect to host fbshs1.zt.foreningenbs.no
  port 22: Connection timed out
  fbshs1 : ok=0 unreachable=1 failed=0
  fcos-1 : ok=6 changed=2
  fcos-2 : ok=6 changed=2
  fcos-3 : ok=6 changed=2
  DEPLOY FAILED: Ansible failed with exit code 4
  ```

- **2026-05-02 23:15–23:16 UTC** — Re-run of same run (`74076398237`) fails identically.
- **2026-05-02 ~23:00 UTC** — Investigation. Confirmed:
  - fcos-3 host can ping and SSH to fbshs1 (172.25.12.4) over ZT.
  - A fresh `alpine` container attached to `fbs0` (tested at 172.25.16.99 and 172.25.28.99) can ping fbshs1 and ARP-resolves it.
  - Inside the running deployer container's network namespace (`172.25.16.51`, MAC `22:f7:e5:ab:e0:9b`), `ping 172.25.12.4` returns `Destination Host Unreachable`; `ip neigh` shows `172.25.12.4 dev eth0 FAILED`.
  - From the host, `ip neigh` shows `172.25.12.4 dev fbs0 lladdr 22:f7:1a:b4:d2:3d STALE` — the host bridge has fbshs1's MAC.
  - `bridge link` shows `ztfbs0 ... master fbs0 state forwarding`; routing on host: `172.25.12.4 dev fbs0 src 172.25.12.3 cache`.
- **2026-05-02 23:35 UTC** — `docker restart deployer` on fcos-3. Inside the new namespace `ip neigh` shows `172.25.12.4 dev eth0 lladdr 22:f7:1a:b4:d2:3d REACHABLE`; ping succeeds.
- **2026-05-02 23:36–23:38 UTC** — Workflow `25258140723` re-run (`74076885369`) completes successfully (2m16s); fbshs1 included.

## Impact

- Image deploys for `otel-collector-agent` via the deployer service have been failing since the workflow was last triggered (2026-05-02 17:58 UTC).
- Other deployer-managed services targeting fcos-1/2/3 are unaffected (they use public hostnames; only fbshs1 routes via ZT-bridged subnet).
- Last successful otel-collector image deploy: prior to fcos-3 reboot on 2026-05-01.

## Root cause

The Docker bridge `fbs0` on fcos-3 carries subnet `172.25.0.0/16` (same as ZT) and is L2-bridged to ZT via `ztfbs0` (slaved to `fbs0`). Containers on `fbs0` are intended to be reachable by, and to reach, ZT peers transparently.

After fcos-3 was rebooted on 2026-05-01 01:00 UTC, the deployer container restarted (`unless-stopped`) but its veth port on `fbs0` no longer participates correctly in the ZT-bridge learning for remote peers: the deployer's namespace cannot ARP-resolve fbshs1 even though the host can, and even though a freshly-launched container on the same bridge (with the same routing) can.

Exact mechanism not confirmed. Same symptom (`no route to host` from a fbs0 container to a remote ZT-bridged peer) was recorded in `2026-05-02-filer-503-after-fcos-3-reboot.md` for the otel-collector-agent container reaching SigNoz at `172.25.16.62:4317` over the 2026-04-30 → 2026-05-02 window. Logs there resumed at 2026-05-02 02:00 UTC, suggesting the otel-collector-agent recovered when restarted (likely by a deploy); the deployer container has been running undisturbed across the reboot.

## Resolution

```sh
ssh root@fcos-3.nrec.foreningenbs.no docker restart deployer
gh run rerun 25258140723 --failed
```

Restart re-establishes ARP/MAC learning for the container's veth on `fbs0` ↔ `ztfbs0`; the rerun then succeeds.

## Follow-ups

- Same as `2026-05-02-filer-503-after-fcos-3-reboot.md`: investigate the underlying ZT-bridge / fbs0 MAC-learning behaviour after host reboot. The two incidents now have overlapping signature; the condition is reusable, not service-specific.
- Add a host-reboot post-action that bounces all containers attached to `fbs0` (or just the deployer), if restart turns out to be the reliable fix.
- Consider monitoring: a synthetic check from a fbs0 container to fbshs1 would have caught this immediately after the 2026-05-01 reboot.
