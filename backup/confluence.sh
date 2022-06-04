#!/bin/bash
set -eu -o pipefail

# run at backup server (currently Henriks server)
# need ssh keys

echo "Taking backup of confluence..."
date

confluence_server=coreos-3.foreningenbs.no

confluence_data_dir=/data/confluence-data
confluence_logs_dir=/data/confluence-logs

# not used since its the same server - for simplification
db_server=coreos-3.foreningenbs.no
db_container_name=mysql-1

timestamp=$(date -u +%Y%m%d-%H%M%S)Z
backup_dir=/data/confluence-backup-staging/$timestamp

echo "Running external commands"

ssh root@$confluence_server <<EOF
  echo "Creating directory for backup"
  mkdir -p "$backup_dir"

  echo "Backing up home dir"
  cd "$confluence_data_dir"
  tar zcf "$backup_dir/home.tgz" .

  echo "Backing up logs dir"
  cd "$confluence_logs_dir"
  tar zcf "$backup_dir/logs.tgz" .

  echo "Creating database dump"
  export MYSQL_PWD=\$(grep hibernate.connection.password /data/confluence-data/confluence.cfg.xml | cut -d'>' -f2 | cut -d'<' -f1)
  docker exec -i -e MYSQL_PWD $db_container_name mysqldump -u confluence --routines --max_allowed_packet=512M --single-transaction confluence | gzip -9 >"$backup_dir/db.sql.gz"
EOF

echo "Fetching data"

scp -r root@$confluence_server:"$backup_dir" "confluence-backup-$timestamp"

ls -lah "confluence-backup-$timestamp"

echo "Removing from server"

ssh root@$confluence_server <<EOF
  rm -rf "$backup_dir"
EOF

echo "Completed backup"
date
