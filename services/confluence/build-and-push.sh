#!/bin/bash
set -eux

image="ghcr.io/blindern/confluence:latest"

docker build --pull -t "$image" .
docker push "$image"
