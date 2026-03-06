# Deployer Troubleshooting

## 502 Bad Gateway (2026-03-06)

**Symptom:** `https://deployer.foreningenbs.no/` returns 502. CI builds fail at the deploy step for the same reason.

**Root cause:** Both deployer containers (primary + secondary) had all gunicorn worker threads deadlocked. Containers were running (`docker ps` showed "Up 41 hours") but not processing requests. TCP accept queue was full (`Recv-Q: 2049`, backlog limit `2048`).

**Diagnosis steps:**

```bash
# 1. Check container status — containers were "Up" but unresponsive
ssh root@fcos-3.nrec.foreningenbs.no "docker ps -a --filter name=deployer"

# 2. Check if port is listening and queue state
ssh root@fcos-3.nrec.foreningenbs.no \
  "nsenter --net --target \$(docker inspect deployer --format '{{.State.Pid}}') ss -tlnp"
# Recv-Q at/above Send-Q limit = workers stuck, not accepting connections

# 3. Verify processes inside container
ssh root@fcos-3.nrec.foreningenbs.no "docker exec deployer cat /proc/1/cmdline | tr '\0' ' '"

# 4. Note: container uses journald logging (log_driver: journald in Ansible)
# docker logs may appear empty — check journalctl instead
ssh root@fcos-3.nrec.foreningenbs.no \
  "journalctl -u docker CONTAINER_NAME=deployer --no-pager --since '1 hour ago'"
```

**Fix:** Restart both containers:

```bash
ssh root@fcos-3.nrec.foreningenbs.no "docker restart deployer deployer-secondary"
```

**Key takeaways:**

- Container being "Up" doesn't mean it's healthy — always check `ss -tlnp` for `Recv-Q` buildup
- Both primary and secondary can deadlock simultaneously since they run the same image on the same host
- CI deploy failures are often a cascading effect of deployer being down, not a code issue
- The `--timeout 900` (15 min) gunicorn setting is very generous and may delay detection of stuck workers
- No healthcheck is configured on the container, so Docker won't auto-restart on hangs
