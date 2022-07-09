#!/bin/bash
set -eux

image="blindern/confluence:latest"

docker build --pull -t "$image" .
docker push "$image"
