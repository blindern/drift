#!/bin/sh
set -eu

if [ -z ${OS_USERNAME:-} ]; then
  >&2 echo "ERROR: You need to source keystone.sh first"
  exit 1
fi

echo "Fetching coreos image"

wget \
  https://stable.release.core-os.net/amd64-usr/current/coreos_production_openstack_image.img.bz2 \
  -O- \
  | bunzip2 > coreos_production_openstack_image.img

echo "Uploading image to uh-iaas"

# Upload to uh-iaas
glance image-create --name Container-Linux \
  --container-format bare \
  --disk-format qcow2 \
  --file coreos_production_openstack_image.img
