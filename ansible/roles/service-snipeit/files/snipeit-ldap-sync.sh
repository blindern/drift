#!/bin/bash
# artisan snipeit:ldap-sync exit codes are broken upstream (returns the summary
# array, PHP int-casts it) - judge success from the JSON summary instead
set -uo pipefail

out=$(docker exec snipeit php artisan snipeit:ldap-sync --json_summary) || {
  echo "docker exec failed"
  exit 1
}

if echo "$out" | grep -q '"error":true'; then
  echo "$out" | head -c 2000
  exit 1
fi

if echo "$out" | grep -q '"status":"error"'; then
  echo "entries failed:"
  echo "$out" | grep -o '"username":"[^"]*"[^}]*"status":"error"' | sed -E 's/.*"username":"([^"]*)".*"note":"([^"]*)".*/\1: \2/'
  exit 1
fi

echo "ldap-sync ok ($(echo "$out" | grep -c -o '"status":"success"') entries)"
