#!/bin/bash
set -eux

tag="$(date -u +%Y%m%d-%H%M)"
image="blindern/simplesamlphp:$tag"

docker build --pull -t "$image" .
docker push "$image"
