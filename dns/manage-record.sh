#!/bin/bash
set -eu

script_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Load .env if exists
if [ -f "$script_dir/.env" ]; then
  # shellcheck source=/dev/null
  source "$script_dir/.env"
fi

foreningenbs_no_id=839052
api_base="https://api.domeneshop.no/v0/domains/$foreningenbs_no_id/dns"

# Flags
apply=false
force=false

usage() {
  cat <<EOF
Usage: $0 <command> [args] [--apply] [--force]

Commands:
  list                      List all *.zt A records
  get <hostname>            Show specific record (e.g., web-1.zt)
  set <hostname> <ip>       Create or update A record
  delete <hostname>         Delete A record

Flags:
  --apply    Actually make changes (default is dry-run)
  --force    Skip confirmation for delete

Environment:
  DOMENESHOP_TOKEN   API token
  DOMENESHOP_SECRET  API secret

Examples:
  $0 list
  $0 set new-service.zt 172.25.16.60
  $0 set new-service.zt 172.25.16.60 --apply
  $0 delete old-service.zt --apply
EOF
  exit 1
}

validate_env() {
  if [ -z "${DOMENESHOP_TOKEN:-}" ]; then
    echo "Error: DOMENESHOP_TOKEN is not set" >&2
    exit 1
  fi
  if [ -z "${DOMENESHOP_SECRET:-}" ]; then
    echo "Error: DOMENESHOP_SECRET is not set" >&2
    exit 1
  fi
}

api_get() {
  curl -sf -u "$DOMENESHOP_TOKEN:$DOMENESHOP_SECRET" "$api_base"
}

api_post() {
  local data="$1"
  curl -sf -u "$DOMENESHOP_TOKEN:$DOMENESHOP_SECRET" \
    -H "Content-Type: application/json" \
    -X POST -d "$data" "$api_base"
}

api_put() {
  local record_id="$1"
  local data="$2"
  curl -sf -u "$DOMENESHOP_TOKEN:$DOMENESHOP_SECRET" \
    -H "Content-Type: application/json" \
    -X PUT -d "$data" "$api_base/$record_id"
}

api_delete() {
  local record_id="$1"
  curl -sf -u "$DOMENESHOP_TOKEN:$DOMENESHOP_SECRET" \
    -X DELETE "$api_base/$record_id"
}

validate_hostname() {
  local hostname="$1"
  if [[ ! "$hostname" =~ \.zt$ ]]; then
    echo "Error: Hostname must end with .zt (got: $hostname)" >&2
    exit 1
  fi
}

validate_ip() {
  local ip="$1"
  if [[ ! "$ip" =~ ^172\.25\.[0-9]+\.[0-9]+$ ]]; then
    echo "Error: IP must be in 172.25.x.x range (got: $ip)" >&2
    exit 1
  fi
}

get_zt_records() {
  api_get | jq '[.[] | select(.type == "A" and (.host | endswith(".zt")))]'
}

find_record() {
  local hostname="$1"
  get_zt_records | jq --arg host "$hostname" '[.[] | select(.host == $host)]'
}

cmd_list() {
  validate_env
  echo "*.zt A records:"
  get_zt_records | jq -r '.[] | "\(.host)\t\(.data)\t(id: \(.id))"' | sort | column -t
}

cmd_get() {
  local hostname="${1:-}"
  if [ -z "$hostname" ]; then
    echo "Error: hostname required" >&2
    usage
  fi
  validate_hostname "$hostname"
  validate_env

  local records
  records=$(find_record "$hostname")
  local count
  count=$(echo "$records" | jq 'length')

  if [ "$count" -eq 0 ]; then
    echo "No record found for $hostname"
    exit 1
  fi

  echo "$records" | jq -r '.[] | "Host: \(.host)\nIP: \(.data)\nID: \(.id)\nTTL: \(.ttl)\n"'
}

cmd_set() {
  local hostname="${1:-}"
  local ip="${2:-}"
  if [ -z "$hostname" ] || [ -z "$ip" ]; then
    echo "Error: hostname and ip required" >&2
    usage
  fi
  validate_hostname "$hostname"
  validate_ip "$ip"
  validate_env

  local records
  records=$(find_record "$hostname")
  local count
  count=$(echo "$records" | jq 'length')

  if [ "$count" -gt 1 ]; then
    echo "Error: Multiple A records found for $hostname. Manual handling required:" >&2
    echo "$records" | jq -r '.[] | "  - \(.data) (id: \(.id))"' >&2
    exit 1
  fi

  local data
  data=$(jq -n --arg host "$hostname" --arg ip "$ip" '{host: $host, type: "A", data: $ip, ttl: 3600}')

  if [ "$count" -eq 1 ]; then
    local record_id current_ip
    record_id=$(echo "$records" | jq -r '.[0].id')
    current_ip=$(echo "$records" | jq -r '.[0].data')

    if [ "$current_ip" = "$ip" ]; then
      echo "Record already exists with same IP: $hostname -> $ip"
      exit 0
    fi

    echo "Update record: $hostname"
    echo "  Current: $current_ip"
    echo "  New:     $ip"
    echo "  ID:      $record_id"

    if [ "$apply" = true ]; then
      api_put "$record_id" "$data"
      echo "Updated."
    else
      echo ""
      echo "(dry-run: use --apply to make changes)"
    fi
  else
    echo "Create record: $hostname -> $ip"

    if [ "$apply" = true ]; then
      api_post "$data"
      echo "Created."
    else
      echo ""
      echo "(dry-run: use --apply to make changes)"
    fi
  fi
}

cmd_delete() {
  local hostname="${1:-}"
  if [ -z "$hostname" ]; then
    echo "Error: hostname required" >&2
    usage
  fi
  validate_hostname "$hostname"
  validate_env

  local records
  records=$(find_record "$hostname")
  local count
  count=$(echo "$records" | jq 'length')

  if [ "$count" -eq 0 ]; then
    echo "No record found for $hostname"
    exit 1
  fi

  if [ "$count" -gt 1 ]; then
    echo "Error: Multiple A records found for $hostname. Manual handling required:" >&2
    echo "$records" | jq -r '.[] | "  - \(.data) (id: \(.id))"' >&2
    exit 1
  fi

  local record_id current_ip
  record_id=$(echo "$records" | jq -r '.[0].id')
  current_ip=$(echo "$records" | jq -r '.[0].data')

  echo "Delete record: $hostname -> $current_ip (id: $record_id)"

  if [ "$apply" != true ]; then
    echo ""
    echo "(dry-run: use --apply to make changes)"
    exit 0
  fi

  if [ "$force" != true ]; then
    read -r -p "Are you sure? [y/N] " response
    if [[ ! "$response" =~ ^[Yy]$ ]]; then
      echo "Aborted."
      exit 1
    fi
  fi

  api_delete "$record_id"
  echo "Deleted."
}

# Parse arguments
command=""
args=()

while [[ $# -gt 0 ]]; do
  case "$1" in
    --apply)
      apply=true
      shift
      ;;
    --force)
      force=true
      shift
      ;;
    -h|--help)
      usage
      ;;
    *)
      if [ -z "$command" ]; then
        command="$1"
      else
        args+=("$1")
      fi
      shift
      ;;
  esac
done

case "${command:-}" in
  list)
    cmd_list
    ;;
  get)
    cmd_get "${args[0]:-}"
    ;;
  set)
    cmd_set "${args[0]:-}" "${args[1]:-}"
    ;;
  delete)
    cmd_delete "${args[0]:-}"
    ;;
  *)
    usage
    ;;
esac
