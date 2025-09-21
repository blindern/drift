#!/bin/bash
set -eux

image="ghcr.io/blindern/simplesamlphp:latest"

docker build --pull -t "$image" .
docker push "$image"
