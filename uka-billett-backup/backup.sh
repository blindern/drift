#!/bin/bash
set -e

# TODO: clean up old backups
# TODO: compress backup before transfering
# TODO: alert if something fails

out=/fbs/drift/uka-billett-backup/uka_billett_backup_$(date +%Y%m%d.%H%M%S).sql

ssh -q -tt -p 2222 core@server2016.blindernuka.no '
  cd ~/infrastructure/server-2018
  /opt/bin/docker-compose exec database sh -c "mysqldump --password=\$MYSQL_ROOT_PASSWORD uka_billett"
  ' >"$out"

gzip -9 "$out"
