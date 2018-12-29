#!/bin/bash
set -eu

if [ -z "${1:-}" ]; then
  echo "Missing new tag"
  exit 1
fi

echo "$1" >version.txt

. version.sh
/opt/bin/docker-compose up -d
