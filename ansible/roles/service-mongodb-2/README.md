# mongodb

## Manual operations

Get password from https://foreningenbs.no/confluence/x/7gUf

Enter shell:

```bash
ssh root@fcos-2.nrec.foreningenbs.no
docker exec -it mongodb-2 mongosh -u superuser -p -- admin
```

List databases:

```text
show dbs
```

List collections:

```text
show collections
```

## Taking a data backup

```bash
ssh root@fcos-2.nrec.foreningenbs.no
docker exec -i mongodb-2 sh -c 'exec mongodump -u superuser --authenticationDatabase admin -d intern --archive' > /var/mnt/data/mongodb-intern-$(date -u +%Y%m%d-%H%M%S).archive
```
