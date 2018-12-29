#!/bin/sh
set -eux
wget https://github.com/coreos/container-linux-config-transpiler/releases/download/v0.9.0/ct-v0.9.0-x86_64-unknown-linux-gnu \
  -O ct
chmod +x ct
