# certs

## ca

We have a self-signed CA we use for all internal purposes.

Steps used to generate:

```bash
openssl genrsa -aes256 -out ca.key 4096
openssl req -x509 -new -nodes -key ca.key -sha256 -days 5475 \
  -out ca.crt \
  -subj "/CN=Foreningen Blindern Studenterhjem"
```

## dhparams

Command used to generate `dhparam.pem`:

```bash
openssl dhparam -out dhparam.pem 4096
```

To get details from this file:

```bash
openssl dhparam -in dhparam.pem -check -text -noout
```

## Generating certs

```bash
# Replace all "someapp" and copy someapp.conf first.
openssl genrsa -out someapp.key 2048
openssl req -new -key someapp.key -out someapp.csr -config someapp.conf
openssl x509 -req -days 3650 \
  -in someapp.csr \
  -out someapp.crt \
  -CA ca.crt -CAkey ca.key -CAcreateserial \
  -extfile someapp.conf \
  -extensions v3_req

openssl req -in someapp.csr -text -noout
openssl x509 -in someapp.crt -text -noout
```
