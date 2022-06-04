# Guides

## Connecting to databases

Connect to the ZeroTier network to have direct access.

### MySQL-databases

You can also access these via https://foreningenbs.no/tools/phpmyadmin/

Credentials to databases is stored in mysql.cnf.

```bash
mysql --defaults-extra-file=mysql.cnf -h mysql-1.zt.foreningenbs.no
```
