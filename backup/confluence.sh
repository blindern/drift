#!/bin/bash
set -eu -o pipefail

# run at backup server (currently Henriks server)
# need ssh keys

echo "Taking backup of confluence..."
date

confluence_server=fcos-1.nrec.foreningenbs.no

confluence_data_dir=/var/mnt/data/confluence-data
confluence_logs_dir=/var/mnt/data/confluence-logs

db_server=fcos-2.nrec.foreningenbs.no
db_container_name=mysql-1

timestamp=$(date -u +%Y%m%d-%H%M%S)Z
backup_dir=/var/mnt/data/confluence-backup-staging/$timestamp
out_dir="confluence-backup-$timestamp"

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

  echo "Extracting database password"
  MYSQL_PWD=\$(grep hibernate.connection.password /var/mnt/data/confluence-data/confluence.cfg.xml | cut -d'>' -f2 | cut -d'<' -f1)
  echo "\$MYSQL_PWD" >"$backup_dir/mysql-password.txt"
EOF

echo "Fetching data (1/2)"
scp -r root@$confluence_server:"$backup_dir" "$out_dir"

mysql_pwd=$(cat "$out_dir/mysql-password.txt")

ssh root@$db_server <<EOF
  echo "Creating directory for backup"
  mkdir -p "$backup_dir"

  echo "Creating database dump"
  export MYSQL_PWD="$mysql_pwd"
  docker exec -i -e MYSQL_PWD $db_container_name mysqldump -u confluence --routines --max_allowed_packet=512M --single-transaction confluence | gzip -9 >"$backup_dir/db.sql.gz"
EOF

echo "Fetching data (2/2)"
scp -r root@$confluence_server:"$backup_dir/*" "$out_dir/"

ls -lah "confluence-backup-$timestamp"

echo "Removing from server"

ssh root@$confluence_server <<EOF
  rm -rf "$backup_dir"
EOF

ssh root@$db_server <<EOF
  rm -rf "$backup_dir"
EOF

echo "Completed backup"
date
