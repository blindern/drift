#!/bin/bash
set -eu

rsync -azv \
  --delete-after \
  --exclude .git \
  root@fcos-2.nrec.foreningenbs.no:/var/mnt/data/ldap-master-config/ provider

rsync -azv \
  --delete-after \
  --exclude .git \
  --exclude oppsett-av-replication \
  root@fcos-3.nrec.foreningenbs.no:/var/mnt/data/ldap-slave-config/ consumer
