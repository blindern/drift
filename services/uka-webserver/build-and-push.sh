#!/bin/bash
set -eux

tag="$(date -u +%Y%m%d-%H%M)"
image="blindernuka/webserver:$tag"

docker build --pull -t "$image" .
docker push "$image"
