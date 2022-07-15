#!/bin/sh
set -e

ln -sf /proc/$$/fd/1 /var/log/apache2/access.log
ln -sf /proc/$$/fd/2 /var/log/apache2/error.log

exec apachectl -DFOREGROUND "$@"
