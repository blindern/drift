FROM nginx:1.29-alpine@sha256:e7257f1ef28ba17cf7c248cb8ccf6f0c6e0228ab9c315c152f9c203cd34cf6d1

RUN set -eux; \
    apk add --no-cache openssl; \
    openssl req -x509 -nodes -days 365 \
      -subj "/C=NO/ST=Oslo/O=FBS/CN=localhost" \
      -newkey rsa:2048 \
      -keyout selfsigned.key \
      -out selfsigned.crt

COPY default.conf /etc/nginx/conf.d/default.conf
