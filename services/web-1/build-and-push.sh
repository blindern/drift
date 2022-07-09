#!/bin/bash
set -eux

image="blindern/internal-web-1:latest"

docker build --pull -t "$image" .
docker push "$image"
