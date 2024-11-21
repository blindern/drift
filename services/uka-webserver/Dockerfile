FROM php:8.4-apache

ENV APACHE_DOCUMENT_ROOT /project/uka/web/public

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
      dirmngr \
      git \
      gnupg2 \
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

# Install nodejs
# From https://github.com/nodejs/docker-node/blob/3e179a85703a6688a26486729b4466a92e818a84/10/stretch/Dockerfile
# gpg keys listed at https://github.com/nodejs/node#release-keys
ENV NODE_VERSION 16.16.0
RUN set -ex \
  && for key in \
    4ED778F539E3634C779C87C6D7062848A1AB005C \
    141F07595B7B3FFE74309A937405533BE57C7D57 \
    94AE36675C464D64BAFA68DD7434390BDBE9B9C5 \
    74F12602B6F1C4E913FAA37AD3A89613643B6201 \
    71DCFD284A79C3B38668286BC97EC7A07EDE3FC1 \
    61FC681DFB92A079F1685E77973F295594EC4689 \
    8FCCA13FEF1D0C2E91008E09770F7A9A5AE15600 \
    C4F0DFFF4E8C1A8236409D08E73BC641CC11F4C8 \
    890C08DB8579162FEE0DF9DB8BEAB4DFCF555EF4 \
    C82FA3AE1CBEDC6BE46B9360C43CEC45C17AB93C \
    DD8F2338BAE7501E3DD5AC78C273792F7D83545D \
    A48C2BEE680E841632CD4E44F07496B3EB3C1762 \
    108F52B48DB57BB0CC439B2997B01419BD92F80A \
    B9E2F5981AA6E0CD28160D9FF13993A75599653C \
  ; do \
      gpg --batch --keyserver hkps://keys.openpgp.org --recv-keys "$key" || \
      gpg --batch --keyserver keyserver.ubuntu.com --recv-keys "$key" ; \
  done \
  && curl -fsSLO --compressed "https://nodejs.org/dist/v$NODE_VERSION/node-v$NODE_VERSION-linux-x64.tar.xz" \
  && curl -fsSLO --compressed "https://nodejs.org/dist/v$NODE_VERSION/SHASUMS256.txt.asc" \
  && gpg --batch --decrypt --output SHASUMS256.txt SHASUMS256.txt.asc \
  && grep " node-v$NODE_VERSION-linux-x64.tar.xz\$" SHASUMS256.txt | sha256sum -c - \
  && tar -xJf "node-v$NODE_VERSION-linux-x64.tar.xz" -C /usr/local --strip-components=1 --no-same-owner \
  && rm "node-v$NODE_VERSION-linux-x64.tar.xz" SHASUMS256.txt.asc SHASUMS256.txt \
  && ln -s /usr/local/bin/node /usr/local/bin/nodejs \
  # smoke tests
  && node --version \
  && npm --version

RUN set -eux; \
    npm install -g uglifycss

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY entrypoint.sh /entrypoint.sh
COPY php.ini /usr/local/etc/php/php.ini
COPY apache-extra.conf /etc/apache2/conf-enabled/apache-extra.conf

# The user we want to switch to when running.
# See entrypoint.sh for description.
ENV CONTAINER_USER app

# The file to copy uid/gid from when creating user.
ENV CONTAINER_USERFILE /project/uka/web/README.md

ENV APACHE_RUN_USER app
ENV APACHE_RUN_GROUP app

WORKDIR /project/uka
ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]
