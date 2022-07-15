#!/bin/sh
set -e

# Handle leftovers from previous run.
rm -f /run/sssd.pid

exec sssd -i -c /etc/sssd/sssd.conf
