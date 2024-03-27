# openldap

For mer informasjon om LDAP-oppsettet se
https://foreningenbs.no/confluence/display/FBS/LDAP

For enkle endringer i LDAP bruk https://foreningenbs.no/tools/phpldapadmin/
([passord](https://foreningenbs.no/confluence/display/IT/LDAP+adminpass))

## Teste OpenLDAP lokalt

```bash
./load-to-dev.sh consumer
docker-compose up
docker-compose exec openldap bash
ldapsearch -Y EXTERNAL -Q -H ldapi:/// -LLL -b ou=Users,dc=foreningenbs,dc=no name
```

## Backup ved endringer

Du kan laste ned backup av LDAP-serverene ved å kjøre:

```bash
./full-backup.sh
```

## Config backup

I mappen `config-backup` er det snapshot av hvordan config-filene
har sett ut. De kan oppdateres ved å kjøre:

```bash
cd config-backup
./update-backup.sh
```

Pass på at filene blir kryptert (kjør `git-crypt status .`) og commit endringen.

## Servere og databaser

Vi har to servere: `ldap-master` (provider) og `ldap-slave` (consumer).

`ldap-master` har tre databaser: `config`, `data` og `accesslog` (for replikering).

`ldap-slave` har to databaser: `config` og `data`.

## Koble til LDAP-server

Ved å gå inn i Docker-instansen kan man koble til uten passord:

```bash
ssh root@fcos-3.nrec.foreningenbs.no
docker exec -it ldap-slave bash
ldapsearch -Y EXTERNAL -Q -H ldapi:/// -LLL -b cn=config dn
```

(`-Y EXTERNAL -Q -H ldapi:///` er trikset her.)

Men man kan også bruke
[admin-passordet](https://foreningenbs.no/confluence/display/IT/LDAP+adminpass)
for enten `cn=admin,dc=foreningenbs,dc=no` (data)
eller `cn=admin,cn=config` (config):

```bash
ldapsearch -x -W -D cn=admin,dc=foreningenbs,dc=no -H ldaps://ldap-master.zt.foreningenbs.no/ -LLL -b ou=Users,dc=foreningenbs,dc=no name
ldapsearch -x -W -D cn=admin,cn=config -H ldaps://ldap-master.zt.foreningenbs.no/ -LLL -b cn=config dn
```

(`-x -W -D xxx -H yyy` er trikset her.)

Legg med `-d1` hvis du får feil. Du må legge til CA-sertifikatet for
å klare å koble til eller justere `TLS_REQCERT` i `/etc/ldap/ldap.conf`.

> Du kan kjøre dette fra [ldap-toolbox](../../ldap-toolbox) som er ferdig satt opp for dette.
> Den inneholder også f.eks. `ldapaddusertogroup` og slikt.

## Eksempler

Lese ut:

```bash
ldapsearch -Y EXTERNAL -Q -H ldapi:/// -LLL -b cn=config dn
ldapsearch -Y EXTERNAL -Q -H ldapi:/// -LLL -b ou=Users,dc=foreningenbs,dc=no name
```

Endre:

```bash
ldapmodify -Y EXTERNAL -Q -H ldapi:/// <<EOF
dn: uid=halvargimnes,ou=Users,dc=foreningenbs,dc=no
changetype: modify
replace: mail
mail: example@example.com
EOF
```

## Replikering

`ldap-slave` speiler `ldap-master`.

Opprinnelig oppsett: https://foreningenbs.no/filer/Grupper/IT-gruppa/Oppsett/LDAP

`contextCSN` er "versjon av databasen" mellom provider og consumer.
Du kan kjøre denne kommandoen på de ulike maskinene og sammenlikne verdien:

```bash
ldapsearch -Y EXTERNAL -Q -H ldapi:/// -LLL -b dc=foreningenbs,dc=no -z1 -s base contextCSN
```

## Dumpe data og laste igjen

```bash
slapcat -n0 -F /etc/ldap/slapd.d -l /tmp/ldap-db0.ldif
slapcat -n1 -F /etc/ldap/slapd.d -l /tmp/ldap-db1.ldif
```

```bash
slapadd -F /etc/ldap/slapd.d -l /tmp/ldap-db1.ldif
```

## Teste endring før restart

```bash
slaptest -F /etc/ldap/slapd.d
```

## Fikse feil checksum ved manuell config-endring

Fra https://gist.github.com/Shaltz/1d65a07a0901a36fb7f1?permalink_comment_id=4579943#gistcomment-4579943

```bash
LDIF='/etc/openldap/slapd.d/cn=config.ldif'
NEW_CRC32="$(tail -n +3 "$LDIF" | python3 -c 'import sys;import zlib;print("%08x"%(zlib.crc32(sys.stdin.buffer.read())))')"
sed -i "0,/# CRC32 .*/ s//# CRC32 ${NEW_CRC32,,}/g" "$LDIF"
```
