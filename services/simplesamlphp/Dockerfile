FROM php:8.1-apache

# Se latest at https://simplesamlphp.org/download/
ENV SIMPLESAMLPHP_VERSION=1.19.6
ENV SIMPLESAMLPHP_SHA256=834bb4a89d63d7498e77cceb49e01b919d1c0a6a3d38a992f905810dad424b7c

ENV COMPOSER_VERSION=2.3.10

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
      git \
      jq \
      unzip \
      wget \
      zip \
    ; \
    rm -rf /var/lib/apt/lists/*

RUN set -eux; \
    # Set up composer.
    echo "$(wget -q -O- https://composer.github.io/installer.sig)  composer-setup.php" >composer-setup.php.sha384; \
    wget -O composer-setup.php https://getcomposer.org/installer; \
    sha384sum -c composer-setup.php.sha384; \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer --quiet; \
    rm composer-setup.php; \
    rm composer-setup.php.sha384

RUN set -eux; \
    # Install SimpleSAMLphp.
    # See https://simplesamlphp.org/docs/stable/simplesamlphp-install
    mkdir /var/simplesamlphp; \
    cd /var/simplesamlphp; \
    curl -fSL "https://github.com/simplesamlphp/simplesamlphp/releases/download/v$SIMPLESAMLPHP_VERSION/simplesamlphp-$SIMPLESAMLPHP_VERSION.tar.gz" \
      -o simplesamlphp.tar.gz; \
    echo "$SIMPLESAMLPHP_SHA256 *simplesamlphp.tar.gz" | sha256sum -c -; \
    tar --strip-components=1 -zxf simplesamlphp.tar.gz; \
    rm simplesamlphp.tar.gz; \
    \
    composer install --ignore-platform-reqs; \
    composer require --ignore-platform-reqs cirrusidentity/simplesamlphp-module-authoauth2 3.1.0

RUN set -eux; \
    cd /var/simplesamlphp; \
    mv composer.json composer.old.json; \
    cat composer.old.json | \
      jq '.repositories |= . + [{"type": "vcs", "url": "https://github.com/blindern/simplesamlphp-module-fbs"}]' \
      >composer.json; \
    composer require --ignore-platform-reqs blindern/simplesamlphp-module-fbs "dev-master#1893ec0"

COPY site.conf /etc/apache2/sites-enabled/000-default.conf

EXPOSE 80
