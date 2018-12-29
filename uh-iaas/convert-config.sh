#!/bin/sh
set -eux
./ct \
  -in-file coreos-config.yml \
  -out-file coreos-config.ign \
  -platform openstack-metadata \
  -files-dir "$PWD" \
  -pretty
