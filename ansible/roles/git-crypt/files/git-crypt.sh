#!/bin/bash
set -eu

root="$(git rev-parse --show-toplevel)"
relative="$(realpath --relative-to="$root" .)"

exec docker run \
  -u $(id -u):$(id -g) \
  -it \
  -w "/repo/$relative" \
  -v "$root":/repo quay.io/lukebond/git-crypt:v1.0.0 \
  "$@"
