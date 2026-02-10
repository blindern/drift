# Guides

## Connecting to databases

### MySQL-databases

You can also access these via https://foreningenbs.no/tools/phpmyadmin/

Credentials to databases is stored in mysql.cnf.

Connect to the ZeroTier network to have direct access.

```bash
mysql --defaults-extra-file=mysql.cnf -h mysql-1.zt.foreningenbs.no
```

### PostgreSQL-database

Credentials are stored in pgpass.conf.

Connect to the ZeroTier network to have direct access.

```bash
PGPASSFILE=pgpass.conf psql -h postgresql-1.zt.foreningenbs.no -U postgres
```

Or via SSH:

```bash
ssh root@fcos-2.nrec.foreningenbs.no
docker exec -it -u postgres postgresql-1 psql
```

## Upgrading PostgreSQL

Standalone playbooks for backup and major version upgrades.

### Backup only

```bash
cd ansible
ansible-playbook postgresql-1-backup.yml
```

### Major version upgrade (dump/restore)

1. Update image in `site.yml` and `postgresql-1-upgrade.yml`
2. Update PGDATA path in `roles/service-postgresql-1/files/postgresql.conf`
   and the volume mount in `roles/service-postgresql-1/tasks/main.yml`
   (PG 18 uses `/var/lib/postgresql/18/docker`, future versions will differ)
3. Update the volume mount in `postgresql-1-upgrade.yml` to match
4. Run the upgrade and deploy:

```bash
cd ansible
# Dump/restore into new data dir
ansible-playbook postgresql-1-upgrade.yml
# Deploy production container with new image
ansible-playbook site.yml -l fcos-2 -t service-postgresql-1

# To retry with an existing backup (skips backup step):
ansible-playbook postgresql-1-upgrade.yml -e pg_backup_file=/var/mnt/data/postgresql-1-backup-XXXXX.sql.xz
```

### Post-upgrade verification

```bash
ssh root@fcos-2.nrec.foreningenbs.no
docker exec -u postgres postgresql-1 psql -c "SELECT version();"
docker exec -u postgres postgresql-1 psql pykota -c "\dt"
```

### Rollback

```bash
ssh root@fcos-2.nrec.foreningenbs.no
docker stop postgresql-1 && docker rm postgresql-1
rm -rf /var/mnt/data/postgresql-1-data
mv /var/mnt/data/postgresql-1-data-pre-upgrade /var/mnt/data/postgresql-1-data
# Revert image + volume mount + postgresql.conf changes, then re-deploy
```

### Cleanup (after stability confirmed)

```bash
ssh root@fcos-2.nrec.foreningenbs.no
rm -rf /var/mnt/data/postgresql-1-data-pre-upgrade
rm /var/mnt/data/postgresql-1-backup-*.sql.xz
```
