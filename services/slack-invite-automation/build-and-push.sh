#!/bin/bash
set -eux

image="blindern/slack-invite-automation:latest"

docker build --pull -t "$image" .
docker push "$image"
