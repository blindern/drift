FROM php:8.5-apache@sha256:eacc0d98992683cb46e4f8f44b2418a0323855dc8b59d32dc54f7a9b90a966dd

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
