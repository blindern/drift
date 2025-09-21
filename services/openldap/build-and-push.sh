#!/bin/bash
set -eux

image="ghcr.io/blindern/openldap:latest"

docker build --pull -t "$image" .
docker push "$image"
