#!/bin/bash
set -eux

image="blindern/openldap:latest"

docker build --pull -t "$image" .
docker push "$image"
