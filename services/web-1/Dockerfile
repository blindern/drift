# Our code is currently not compatible with PHP 7.
FROM php:5.6-apache

ENV NODE_VERSION=12.14.0

RUN set -eux; \
    apt-get update; \
    apt-get upgrade -y; \
    apt-get install -y --no-install-recommends \
      dirmngr \
      git \
      gnupg2 \
      graphviz \
      libapache2-mod-auth-mellon \
      libfreetype6-dev \
      libjpeg62-turbo-dev \
      libmcrypt-dev \
      libpng-dev \
      libssl-dev \
      libxslt1-dev \
      msmtp \
      zlib1g-dev \
    ; \
    rm -rf /var/lib/apt/lists/*

RUN set -eux; \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/; \
    docker-php-ext-install -j$(nproc) \
      bcmath \
      gettext \
      gd \
      mcrypt \
      mysql \
      mysqli \
      pdo_mysql \
      xsl \
      zip \
    ; \
    a2enmod \
      ldap \
      proxy_http \
      proxy_html \
      proxy_fcgi \
      rewrite \
      remoteip

RUN set -eux; \
    curl -fsSLO --compressed "https://nodejs.org/dist/v$NODE_VERSION/node-v$NODE_VERSION-linux-x64.tar.xz"; \
    tar -xJf "node-v$NODE_VERSION-linux-x64.tar.xz" -C /usr/local --strip-components=1 --no-same-owner; \
    rm "node-v$NODE_VERSION-linux-x64.tar.xz"

COPY --from=composer /usr/bin/composer /usr/bin/composer

# We will pass all files during run.
RUN rm -f /etc/apache2/sites-enabled/*
