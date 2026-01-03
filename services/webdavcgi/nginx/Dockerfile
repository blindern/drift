FROM nginx:1.29-alpine@sha256:8491795299c8e739b7fcc6285d531d9812ce2666e07bd3dd8db00020ad132295

RUN set -eux; \
    apk add --no-cache openssl; \
    openssl req -x509 -nodes -days 365 \
      -subj "/C=NO/ST=Oslo/O=FBS/CN=localhost" \
      -newkey rsa:2048 \
      -keyout selfsigned.key \
      -out selfsigned.crt

COPY default.conf /etc/nginx/conf.d/default.conf
