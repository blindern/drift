FROM node:24-bookworm-slim@sha256:6f7b03f7c2c8e2e784dcf9295400527b9b1270fd37b7e9a7285cf83b6951452d AS node

FROM php:8.5-apache@sha256:eacc0d98992683cb46e4f8f44b2418a0323855dc8b59d32dc54f7a9b90a966dd

ENV APACHE_DOCUMENT_ROOT /project/uka/web/public

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
      git \
      graphviz \
      libfreetype6-dev \
      libjpeg62-turbo-dev \
      libmcrypt-dev \
      libpng-dev \
      libxslt1-dev \
      libzip-dev \
      zlib1g-dev \
    ; \
    rm -rf /var/lib/apt/lists/*; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j$(nproc) \
      bcmath \
      gd \
      mysqli \
      pdo_mysql \
      xsl \
      zip \
    ; \
    sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf; \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf; \
    a2enmod rewrite remoteip

COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN set -eux; \
    ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm; \
    ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx; \
    node --version; \
    npm --version

RUN set -eux; \
    npm install -g uglifycss

COPY --from=composer@sha256:5946476338742b200bb9ff88f8be56275ddae4b3949c72305cb0dbf10cfcb760 /usr/bin/composer /usr/bin/composer

COPY entrypoint.sh /entrypoint.sh
COPY php.ini /usr/local/etc/php/php.ini
COPY apache-extra.conf /etc/apache2/conf-enabled/apache-extra.conf

# The user we want to switch to when running.
# See entrypoint.sh for description.
ENV CONTAINER_USER app

# Pre-create the user so it exists even when the entrypoint takes the
# uid-0 path (Docker Desktop mounts are root-owned), where no user is
# created and Apache would fail on APACHE_RUN_USER=app.
RUN useradd -m app

# The file to copy uid/gid from when creating user.
ENV CONTAINER_USERFILE /project/uka/web/README.md

ENV APACHE_RUN_USER app
ENV APACHE_RUN_GROUP app

WORKDIR /project/uka
ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]
