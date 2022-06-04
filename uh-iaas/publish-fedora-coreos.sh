#!/bin/bash
set -eu

stream=stable

if [ -z ${OS_USERNAME:-} ]; then
  >&2 echo "ERROR: You need to source keystone.sh first"
  exit 1
fi

echo "Fetching Fedora coreos image"

# docker run \
#   --pull=always \
#   --rm \
#   -it \
#   -v "$PWD:/data" \
#   -w /data \
#   -u "$(id -u)" \
#   quay.io/coreos/coreos-installer:release download -s "$stream" -p openstack -f qcow2.xz --decompress

file=$(ls -1 fedora-coreos-*.qcow2 | sort | tail -1)
name=${file:0:-6} # pull off .qcow2

echo "Uploading image to uh-iaas"

openstack image create \
  --disk-format qcow2 \
  --min-disk=10 \
  --min-ram=2 \
  --file "$file" \
  "$name"
