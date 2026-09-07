FROM nginx:1.31-alpine@sha256:72ba65eb42c10344912a84ff42408db7d34f2feb642204570ab8fc5ffd29f1d3

RUN set -eux; \
    apk add --no-cache openssl; \
    openssl req -x509 -nodes -days 365 \
      -subj "/C=NO/ST=Oslo/O=FBS/CN=localhost" \
      -newkey rsa:2048 \
      -keyout selfsigned.key \
      -out selfsigned.crt

COPY default.conf /etc/nginx/conf.d/default.conf
