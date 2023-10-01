#!/bin/bash
set -eu

if [ -z "$DOMENESHOP_TOKEN" ]; then
  echo "DOMENESHOP_TOKEN is not set"
  exit 1
fi

if [ -z "$DOMENESHOP_SECRET" ]; then
  echo "DOMENESHOP_SECRET is not set"
  exit 1
fi

FORENINGENBS_NO_ID=839052

curl -u "$DOMENESHOP_TOKEN:$DOMENESHOP_SECRET" "https://api.domeneshop.no/v0/domains/$FORENINGENBS_NO_ID/dns" | jq 'sort_by(.type, .host, .data)' >records-foreningenbs.no-snapshot.json
