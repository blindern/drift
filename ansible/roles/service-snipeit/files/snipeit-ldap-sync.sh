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

admin_uids=$(docker exec ldap-master ldapsearch -x -LLL \
  -b cn=admin,ou=Groups,dc=foreningenbs,dc=no -s base member |
  sed -nE 's/^member: uid=([^,]+),.*/\1/p')

if [ -z "$admin_uids" ]; then
  echo "no members found in ldap cn=admin, refusing to sync groups"
  exit 1
fi

# tinker exits 0 even on exceptions - judge success from the output marker
gout=$(docker exec -e ADMIN_UIDS="$admin_uids" snipeit php artisan tinker --execute='
$uids = preg_split("/\s+/", trim(getenv("ADMIN_UIDS")));
$adminIds = App\Models\User::whereIn("username", $uids)->pluck("id");
if (count($adminIds) === 0) {
  throw new Exception("no admin uids matched any snipeit user");
}
App\Models\Group::where("name", "Admin")->firstOrFail()->users()->sync($adminIds);
$bruker = App\Models\Group::where("name", "Bruker")->firstOrFail();
$missing = App\Models\User::where("ldap_import", 1)
  ->whereDoesntHave("groups", fn ($q) => $q->where("group_id", $bruker->id))
  ->pluck("id");
foreach ($missing as $id) {
  App\Models\User::find($id)->groups()->attach($bruker->id);
}
echo "group-sync ok (admins=" . count($adminIds) . " baseline_added=" . count($missing) . ")\n";
')

echo "$gout"
if ! echo "$gout" | grep -q "group-sync ok"; then
  echo "group sync failed"
  exit 1
fi
