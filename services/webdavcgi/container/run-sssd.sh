#!/bin/sh
set -e

# Handle leftovers from previous run.
rm -f /run/sssd.pid

chmod 600 /etc/sssd/sssd.conf
exec sssd -i -c /etc/sssd/sssd.conf
