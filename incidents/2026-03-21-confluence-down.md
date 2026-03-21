# 2026-03-21: Confluence down — MySQL unreachable after fcos-2 reboot

## Timeline

- **01:04 UTC** — fcos-2 rebooted (scheduled kernel update: 6.18.13)
- **~01:05 UTC** — All containers on fcos-2 restarted, ZeroTier reconnected
- **~02:00 UTC** — Confluence DB connection pool exhausted (mysql-1 container unreachable over ZeroTier from fcos-1)
- **02:20 UTC** — First e2e test failure detected
- **08:55 UTC** — Restarted mysql-1 container on fcos-2, connectivity restored
- **08:56 UTC** — Restarted confluence on fcos-1
- **08:59 UTC** — Confluence back up (HTTP 200)

## Impact

- Confluence unavailable for ~7 hours
- E2E tests failing continuously (wiki.spec.ts timeout on `#login-link`)

## Root cause

After fcos-2 rebooted, the mysql-1 container's network interface was not properly reachable over ZeroTier from other hosts. Specifically:

- Host-to-host ZeroTier connectivity worked (fcos-1 172.25.12.1 <-> fcos-2 172.25.12.2)
- Other containers on fcos-2 were reachable (e.g. users-api 172.25.16.30, signoz-otel 172.25.16.62)
- mysql-1 (172.25.16.40) was unreachable: ARP entry stuck as "(incomplete)", ICMP 100% packet loss
- Bridge FDB had the correct MAC entry, arping occasionally got a reply, but normal traffic didn't flow

Likely a Docker bridge/macvlan timing issue during boot where mysql-1's veth was attached to the fbs0 bridge before ZeroTier was fully ready, causing the bridge to not properly learn or forward frames for that specific interface.

## Resolution

1. Restarted mysql-1 container on fcos-2 — this recreated the veth and re-attached it to fbs0, fixing ZeroTier reachability
2. Restarted confluence on fcos-1 — reconnected to MySQL

## Learnings

- **Container restart order matters after host reboot.** Docker restarts containers in parallel, but ZeroTier bridge readiness is not guaranteed. A container that starts before ztfbs0 is fully bridged may get a broken network path.
- **The e2e wiki test is an indirect indicator.** The actual failure was ZeroTier networking, but the symptom was a Confluence HTTP 500 / timeout. The error message ("waiting for locator '#login-link'") doesn't hint at the real cause.
- **Not all containers are equally affected.** After this reboot, most containers worked fine — only mysql-1 had the issue. This makes it hard to detect with simple host-level health checks.

## Potential improvements

- Add a health check that verifies cross-host container connectivity (e.g. fcos-1 pinging key services on fcos-2)
- Consider adding a post-boot script that restarts containers if ZeroTier connectivity fails after boot
- Gatus already monitors `confluence` internally — check if it alerted (it checks `http://confluence.zt.foreningenbs.no:8090` every 1m, but if the issue is ZeroTier from gatus's host, it may have the same blind spot)
