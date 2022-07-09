#!/bin/bash
set -eux

image="blindernuka/webserver:latest"

docker build --pull -t "$image" .
docker push "$image"
