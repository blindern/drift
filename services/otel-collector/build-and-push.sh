#!/bin/bash
set -eux

image="ghcr.io/blindern/otel-collector:latest"

docker build --pull --platform linux/amd64 -t "$image" .
docker push "$image"
