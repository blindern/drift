#!/bin/bash
set -eux

image="ghcr.io/blindern/ldap-toolbox:latest"

docker build --pull -t "$image" .
docker push "$image"
