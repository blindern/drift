#!/bin/bash
set -eux

image="ghcr.io/blindern/slack-invite-automation:latest"

docker build --pull -t "$image" .
docker push "$image"
