#!/bin/bash
set -eux

tag="$(date -u +%Y%m%d-%H%M)"
image="blindern/internal-web-1:$tag"

docker build --pull -t "$image" .
docker push "$image"
