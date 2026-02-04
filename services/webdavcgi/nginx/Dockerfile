FROM nginx:1.29-alpine@sha256:4870c12cd2ca986de501a804b4f506ad3875a0b1874940ba0a2c7f763f1855b2

RUN set -eux; \
    apk add --no-cache openssl; \
    openssl req -x509 -nodes -days 365 \
      -subj "/C=NO/ST=Oslo/O=FBS/CN=localhost" \
      -newkey rsa:2048 \
      -keyout selfsigned.key \
      -out selfsigned.crt

COPY default.conf /etc/nginx/conf.d/default.conf
