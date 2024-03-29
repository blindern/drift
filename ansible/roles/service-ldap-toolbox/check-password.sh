#!/bin/bash
set -eu

# Verify password contains no newline due to editing.
if [ -z "$(tail -c 1 files/ldapscripts.passwd)" ]; then
  echo "Password contains newline!"
else
  echo "Password OK"
fi
