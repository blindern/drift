#!/bin/bash
set -eux

image="blindern/simplesamlphp:latest"

docker build --pull -t "$image" .
docker push "$image"
