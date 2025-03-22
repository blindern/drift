# webdavcgi

https://foreningenbs.no/filer/

This service runs webdavcgi (https://github.com/DanRohde/webdavcgi)
with authentication using "foreningsbruker".

## Technical details

- webdavcgi itself is run as a CGI program on every request
- Apache httpd is used to handle requests and call webdavcgi
- Authentication is done by either the Mellon module or direct
  LDAP authentication by Apache. Using direct LDAP allows
  WebDAV clients to use the service
- NSS and SSSD is set up with LDAP, so the service have
  users and groups from LDAP available
- Files are stored using extended ACL

## Development

You need to be connected to the ZeroTier network so
that you can reach the LDAP server.

```bash
# Place any files in share folder to see them in the website.
# You might need to change permissions on the folder.
mkdir share

# Run server.
docker compose up --build
```

https://localhost:8820/filer/

## Initial provision

After the service has started for the first time:

This is needed since webdavcgi run as the individual users,
so the script can access the same shared files.

```bash
cd /mnt/data

chown root:root webdav.db
chmod 666 webdav.db

touch webdavcgi.log
chown root:root webdavcgi.log
chmod 666 webdavcgi.log
```

## Workaround for gvfs

Accessing `davs://localhost:8820/filer/`  might give an error
`Message is already in session queue`. It seems this is because gvfs
sends a OPTION call to `/filer` instead of `/filer/`.

Use `davs://localhost:8820/filer/Grupper` or some other subpath
instead. After connected you should be able to traverse up to
the root directory.
