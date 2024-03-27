#!/bin/bash
set -eu

if [ "$1" = "provider" ]; then
  server="fcos-2.nrec.foreningenbs.no"
  dataname="ldap-master"
elif [ "$1" = "consumer" ]; then
  server="fcos-3.nrec.foreningenbs.no"
  dataname="ldap-slave"
else
  echo "Usage: $0 <provider|consumer>"
  exit 1
fi

if [ -e data/dev ]; then
  echo "Delete data/dev first"
  exit 1
fi

mkdir -p data/dev
scp -rp "root@$server:/var/mnt/data/$dataname-config" data/dev/config
scp -rp "root@$server:/var/mnt/data/$dataname-data" data/dev/data

# Remove TLS stuff for local dev to avoid having to deal
# with decrypting the key for it in this repo.
sed -i 's|olcTLSCertificateKeyFile: /certs/slapd.key|#olcTLSCertificateKeyFile: /certs/slapd.key|' "data/dev/config/cn=config.ldif"

echo "Finished!"
