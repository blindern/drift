# 2026-05-02: webdavcgi (foreningenbs.no/filer/) returning 503 after fcos-3 reboot

## Timeline

- **2026-05-01 01:00:28 UTC** — fcos-3 rebooted by Zincati / `rpm-ostreed` (Fedora CoreOS automatic OS update).
- **2026-05-01 01:16:31 UTC** — Host back up; webdavcgi container auto-started via `restart_policy: unless-stopped`. Apache parent got PID 15. cgid daemon tried to bind `/var/run/apache2/socks/cgisock.15` and failed with `(98) Address already in use`; logged `[cgid:crit] AH01238: cgid daemon failed to initialize`. Apache parent stayed up; cgid daemon was not respawned.
- **2026-05-01 01:19 UTC** — First scheduled `webdavcgi.spec.ts` e2e failure. Tests continued failing hourly.
- **2026-05-02 ~11:00 UTC** — User reported `/filer/` down; investigation started.
- **2026-05-02 11:50 UTC** — Removed stale `cgisock.*` files inside container, `docker restart webdavcgi`. cgid daemon started; `/filer/` returned 200 for authenticated requests.
- **2026-05-02 ~12:00 UTC** — Long-term fix (entrypoint cleans stale state) committed.

## Impact

- `https://foreningenbs.no/filer/` returned HTTP 503 for every authenticated request for ~34 hours.
- Login/SAML flow worked; the failure was in post-auth CGI execution.
- Hourly e2e tests failed continuously (`webdavcgi.spec.ts` only; other tests passed).

## Root cause

`/var/run/apache2/socks/` lives in the container's writable layer (not tmpfs), so socket files persist across container restarts. Inside the container the directory contained `cgisock.28` from the original 2026-04-25 start as well as a `cgisock.15` from an earlier Apache run that had also been PID 15 (an Apache "configured -- resuming" entry with PID 15 was recorded at 2026-04-29 01:20).

When fcos-3 rebooted on 2026-05-01:

1. Container restarted (`unless-stopped`).
2. Supervisord spawned `apachectl -DFOREGROUND`; the new Apache parent received PID 15.
3. mod_cgid forked its helper daemon, which tried to bind `/var/run/apache2/socks/cgisock.15`.
4. The bind failed with EADDRINUSE because of the leftover `cgisock.15`.
5. The cgid daemon exited; mod_cgid logged `AH01238: cgid daemon failed to initialize`. mod_cgid does not retry.
6. Every CGI request returned 503.

No recent change to webdavcgi or its config triggered the incident; the host reboot did.

## Resolution

Immediate (live container):

```sh
docker exec webdavcgi sh -c 'rm -f /var/run/apache2/socks/cgisock.*'
docker restart webdavcgi
```

Long-term (`services/webdavcgi/container/run-apache2.sh`):

```sh
rm -f /var/run/apache2/socks/cgisock.* /var/run/apache2/apache2.pid
exec apachectl -DFOREGROUND "$@"
```

## Observations

- All host-level logs from fcos-{1,2,3} were missing from SigNoz between 2026-04-30 ~10:00 UTC and 2026-05-02 02:00 UTC (~42 hours). Only fbshs1 (SigNoz's own host) kept ingesting. fcos-3 journald around the start of the gap shows `dial tcp 172.25.16.62:4317: connect: no route to host` from the otel-collector-agent. The first webdavcgi 503 visible in SigNoz was 2026-05-02 02:23, but the issue started 2026-05-01 01:16.
- mod_cgid does not retry initialization. Once the cgid daemon fails to bind its socket at startup, the parent Apache keeps serving but every CGI request hits a missing socket.
- `webdavcgi.spec.ts` failed within 3 minutes of the reboot and continued failing hourly until the fix.

## Follow-ups

- Investigate the SigNoz log shipping gap (otel-collector-agent on fcos hosts vs. SigNoz on fbshs1, OTLP `172.25.16.62:4317`).
- Check whether other containers using `unless-stopped` and Apache mod_cgid have the same stale-socket failure mode.
