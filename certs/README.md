# certs

## dhparams

Command used to generate `dhparam.pem`:

```bash
openssl dhparam -out dhparam.pem 4096
```

To get details from this file:

```bash
openssl dhparam -in dhparam.pem -check -text -noout
```
