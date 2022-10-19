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

As of Oct 2022 the PostgreSQL database is only
used to store print jobs from Pykota.

See https://foreningenbs.no/confluence/x/twcf
for more details on Pykota usage.

```bash
ssh root@fcos-2.nrec.foreningenbs.no
docker exec -it -u postgres postgresql-1 psql pykota
```
