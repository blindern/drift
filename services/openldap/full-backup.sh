#!/bin/bash
set -eu

timestamp=$(date -u +%Y%m%d-%H%M%SZ)
mkdir -p "backups/$timestamp/provider" "backups/$timestamp/consumer"

echo "Storing to backups/$timestamp"

# Provider
# Has 3 dbs (config + main + accesslogs)

ssh -T root@fcos-2.nrec.foreningenbs.no <<EOF
  set -e
  docker exec ldap-master slapcat -F /etc/ldap/slapd.d -n0 >/tmp/db0.ldif
  docker exec ldap-master slapcat -F /etc/ldap/slapd.d -n1 >/tmp/db1.ldif
  docker exec ldap-master slapcat -F /etc/ldap/slapd.d -n2 >/tmp/db2.ldif
EOF
scp "root@fcos-2.nrec.foreningenbs.no:/tmp/db*.ldif" "backups/$timestamp/provider"
ssh -T root@fcos-2.nrec.foreningenbs.no <<EOF
  set -e
  rm /tmp/db0.ldif /tmp/db1.ldif /tmp/db2.ldif
EOF

# Consumer
# Has 2 dbs (config + main)

ssh -T root@fcos-3.nrec.foreningenbs.no <<EOF
  set -e
  docker exec ldap-slave slapcat -F /etc/ldap/slapd.d -n0 >/tmp/db0.ldif
  docker exec ldap-slave slapcat -F /etc/ldap/slapd.d -n1 >/tmp/db1.ldif
EOF
scp "root@fcos-3.nrec.foreningenbs.no:/tmp/db*.ldif" "backups/$timestamp/consumer"
ssh -T root@fcos-3.nrec.foreningenbs.no <<EOF
  set -e
  rm /tmp/db0.ldif /tmp/db1.ldif
EOF

echo "Finished!"
