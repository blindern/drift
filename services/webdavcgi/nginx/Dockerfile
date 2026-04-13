FROM nginx:1.29-alpine@sha256:582c496ccf79d8aa6f8203a79d32aaf7ffd8b13362c60a701a2f9ac64886c93d

RUN set -eux; \
    apk add --no-cache openssl; \
    openssl req -x509 -nodes -days 365 \
      -subj "/C=NO/ST=Oslo/O=FBS/CN=localhost" \
      -newkey rsa:2048 \
      -keyout selfsigned.key \
      -out selfsigned.crt

COPY default.conf /etc/nginx/conf.d/default.conf
