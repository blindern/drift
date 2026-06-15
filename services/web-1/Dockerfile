FROM php:8.5-apache@sha256:65094755171975e565538ddbfa589847548e80d15adfbb9064a20b86f8485215

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
