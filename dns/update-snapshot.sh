#!/bin/bash
set -eu

script_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Load .env if exists
if [ -f "$script_dir/.env" ]; then
  # shellcheck source=/dev/null
  source "$script_dir/.env"
fi

if [ -z "${DOMENESHOP_TOKEN:-}" ]; then
  echo "DOMENESHOP_TOKEN is not set"
  exit 1
fi

if [ -z "${DOMENESHOP_SECRET:-}" ]; then
  echo "DOMENESHOP_SECRET is not set"
  exit 1
fi

foreningenbs_no_id=839052

curl -u "$DOMENESHOP_TOKEN:$DOMENESHOP_SECRET" "https://api.domeneshop.no/v0/domains/$foreningenbs_no_id/dns" | jq 'sort_by(.type, .host, .data)' >records-foreningenbs.no-snapshot.json
