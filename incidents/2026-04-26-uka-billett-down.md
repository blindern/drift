# 2026-04-26: uka-billett down — proxy container unreachable across ZeroTier after auto-deploy

## Timeline

- **~13:10:05 UTC** — Auto-deploy triggered by new uka-billett-fpm image (PR #208 merging Laravel 13 upgrade). Ansible recreated `uka-billett-fpm` and `uka-billett-proxy` containers on fcos-1.
- **13:11:45 UTC** — First 504 in nginx-front-1 logs (`upstream timed out (110: Operation timed out) while connecting to upstream ... 172.25.16.48:80`).
- **~13:14 UTC** — Hourly e2e tests started failing (`api.spec.ts` got 504 instead of 200 on `/api/eventgroup`).
- **~13:50 UTC** — Investigation started, identified that nginx-front-1 (fcos-3) could not reach `uka-billett-proxy` (172.25.16.48), but could reach every other container on fcos-1 including `uka-billett-fpm` (.49) and `uka-billett-frontend` (.50).
- **13:49:51 UTC** — Restarted `uka-billett-proxy` on fcos-1.
- **~13:50 UTC** — Connectivity restored, public site returning HTTP 200.

## Impact

- `billett.blindernuka.no` returned 504 for all public requests for ~40 min
- Hourly e2e tests failed (`api.spec.ts`)
- Initially looked like a Laravel 13 upgrade failure (the e2e job ran right after the Laravel 13 PR was merged)

## Root cause

Same class of issue as 2026-03-21-confluence-down: a container came up with a broken bridge/ZeroTier path.

- `uka-billett-proxy` (172.25.16.48) was running and worked locally on fcos-1
- ARP across ZeroTier returned the correct MAC, but actual L3 traffic to/from the container did not flow across hosts
- From inside the proxy container: could reach fcos-1 host (172.25.12.1) but not fcos-2 or fcos-3
- From other hosts: could ping every other container on fcos-1 (fpm, frontend, mysql, webserver) but not the proxy
- Docker config (HostConfig, sysctls, veth, bridge attachment) was identical to working containers

The auto-deploy via deployer service recreated both fpm and proxy containers. fpm came up fine; proxy came up with a stuck network path.

## Resolution

`sudo docker restart uka-billett-proxy` on fcos-1 — the new veth attached cleanly and connectivity was restored.

## Learnings

- **Recurrence of the confluence-down pattern.** Same symptom (single container unreachable across ZT, others fine, local OK), different trigger (auto-deploy this time, host reboot last time). The restart-the-container fix worked again.
- **Misleading attribution.** The Laravel 13 PR merge happened seconds before the failure, and the post-deploy e2e tests went red, so it looked like an upgrade bug. The Laravel deploy itself was successful — fpm container was healthy. Only the proxy was broken, and that's the same image as before.
- **Detection lag.** First user-impacting failure at 13:11; investigation started ~40 min later. Gatus monitors `billett.blindernuka.no` but didn't escalate fast enough (or wasn't checked).

## Potential improvements

- Same as 2026-03-21: cross-host container connectivity health check would have caught this immediately
- Consider whether the deployer service should verify post-deploy reachability of the proxy from another host before declaring success
- Slack alert from Gatus on billett 504s (currently only pings to `/`, which returns 504 too — should be alerting)
