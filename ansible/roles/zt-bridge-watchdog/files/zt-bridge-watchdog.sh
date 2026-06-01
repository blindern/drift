#!/usr/bin/env bash
# Detects and recovers containers stranded on the ZeroTier L2 bridge.
#
# Background: incidents/2026-06-01-login-down-postgres-users-api-unreachable-after-fcos-2-reboot.md
# When a container on the fbs0 bridge is recreated (host reboot / deploy) its
# MAC can come up without a bridge-route on peer ZeroTier nodes: the container
# works locally but is isolated cross-host in BOTH directions, while its
# siblings on the same host are fine. Only `docker restart` of that container
# fixes it.
#
# This runs on each host and checks ITS OWN running containers from inside each
# container's network namespace — a stranded container cannot reach ANY peer
# host, a healthy one can. Detection and the `docker restart` are both local; no
# cross-host SSH and, crucially, no probing of other machines' services: it only
# pings the neutral peer HOST IPs, so it is agnostic to whether any app is up.
#
# Deliberately conservative about acting:
#   - only RUNNING containers are considered; a stopped service is never started
#   - containers younger than GRACE_SEC are skipped, so a deploy/upgrade
#     recreating a container is not raced
#   - FAIL_THRESHOLD consecutive failures + COOLDOWN_SEC between restarts
#   - if more than ZTWD_MAX_HEAL containers look stranded at once it's a
#     host-level ZeroTier fault (restart won't help) — alert only

set -u

PEER_IPS="${PEER_IPS:?space-separated peer host ZT IPs required}"
STATE_DIR="${STATE_DIR:-/var/lib/zt-bridge-watchdog}"
PING_TIMEOUT="${PING_TIMEOUT:-2}"
GRACE_SEC="${GRACE_SEC:-180}"
FAIL_THRESHOLD="${FAIL_THRESHOLD:-3}"
COOLDOWN_SEC="${COOLDOWN_SEC:-900}"
AUTO_HEAL="${AUTO_HEAL:-true}"
MAX_HEAL="${ZTWD_MAX_HEAL:-5}"
EXCLUDE="${EXCLUDE:-otel-collector-agent}"

mkdir -p "$STATE_DIR"
log() { echo "zt-bridge-watchdog: $*"; }

# Healthy if the container can ping ANY peer host. A stranded MAC reaches none
# (the echo reply cannot be routed back to it); a single down/unreachable peer
# is tolerated because only one needs to answer.
reaches_a_peer() {
  local pid=$1 ip
  for ip in $PEER_IPS; do
    nsenter -t "$pid" -n ping -c1 -W "$PING_TIMEOUT" "$ip" >/dev/null 2>&1 && return 0
  done
  return 1
}

now=$(date +%s)
declare -a stranded=()

# IP is the last (possibly multi-token) field so it can't shift the others.
# Docker's network order is not stable, so scan all addresses for the fbs0 one.
while read -r name status started_at pid ips; do
  # zerotier-one is hardcoded out: `docker restart zerotier-one` un-enslaves
  # ztfbs0 and black-holes all cross-host traffic (see incident report).
  case " zerotier-one $EXCLUDE " in *" $name "*) continue ;; esac
  [ "$status" = running ] || continue
  ip=""
  for addr in $ips; do
    case "$addr" in 172.25.*) ip=$addr; break ;; esac
  done
  [ -n "$ip" ] || continue   # only fbs0-attached
  [ "$pid" -gt 0 ] 2>/dev/null || continue
  started=$(date -d "$started_at" +%s 2>/dev/null || echo 0)
  # Skip freshly (re)created containers so deploys/upgrades are not raced.
  [ $((now - started)) -lt "$GRACE_SEC" ] && continue

  state="$STATE_DIR/${name}"
  fails=0; last_heal=0
  [ -f "$state" ] && read -r fails last_heal < "$state"

  if reaches_a_peer "$pid"; then
    [ "$fails" -ne 0 ] && log "RECOVERED $name ($ip)"
    fails=0
  else
    fails=$((fails + 1))
    log "STRANDED $name ($ip) consecutive=$fails"
    [ "$fails" -ge "$FAIL_THRESHOLD" ] && stranded+=("$name")
  fi
  echo "$fails $last_heal" > "$state"
done < <(docker ps -q | xargs -r docker inspect \
           -f '{{.Name}} {{.State.Status}} {{.State.StartedAt}} {{.State.Pid}} {{range .NetworkSettings.Networks}}{{.IPAddress}} {{end}}' \
           2>/dev/null | sed 's#^/##')

[ "${#stranded[@]}" -eq 0 ] && exit 0

if [ "${#stranded[@]}" -gt "$MAX_HEAL" ]; then
  log "ALERT ${#stranded[@]} containers stranded (${stranded[*]}) — host-level ZeroTier fault, not auto-restarting"
  exit 0
fi

for name in "${stranded[@]}"; do
  state="$STATE_DIR/${name}"
  read -r fails last_heal < "$state"
  if [ $((now - last_heal)) -lt "$COOLDOWN_SEC" ]; then
    log "$name in cooldown ($((COOLDOWN_SEC - (now - last_heal)))s left), not restarting"
    continue
  fi
  if [ "$AUTO_HEAL" != "true" ]; then
    log "ALERT would restart $name (AUTO_HEAL=false)"
    continue
  fi
  log "HEAL docker restart $name"
  if docker restart "$name"; then
    echo "0 $now" > "$state"
  fi
done
