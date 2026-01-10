FROM php:8.4-apache@sha256:895001a3ae5e48ce21952cdb517e424e484aeadbfceb071145fc17aab5172dd0

RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --no-install-recommends \
      dirmngr \
      gnupg2 \
      graphviz \
      libapache2-mod-auth-mellon \
      libssl-dev \
      msmtp \
      zlib1g-dev \
    ; \
    rm -rf /var/lib/apt/lists/*

RUN set -eux; \
    a2enmod \
      ldap \
      proxy_http \
      proxy_html \
      proxy_fcgi \
      rewrite \
      remoteip

# We will pass all files during run.
RUN rm -f /etc/apache2/sites-enabled/*
