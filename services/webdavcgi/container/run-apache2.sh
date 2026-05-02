#!/bin/sh
set -e

ln -sf /proc/$$/fd/1 /var/log/apache2/access.log
ln -sf /proc/$$/fd/2 /var/log/apache2/error.log

# /var/run/apache2 persists across container restarts. If a new Apache
# parent reuses a PID with a leftover cgisock.<pid>, mod_cgid fails to bind
# it (EADDRINUSE) and all CGI returns 503. See
# incidents/2026-05-02-filer-503-after-fcos-3-reboot.md.
rm -f /var/run/apache2/socks/cgisock.* /var/run/apache2/apache2.pid

exec apachectl -DFOREGROUND "$@"
