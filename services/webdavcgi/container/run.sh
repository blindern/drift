#!/bin/sh
set -e

exec /usr/bin/supervisord -c /etc/supervisord.conf
