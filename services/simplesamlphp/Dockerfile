FROM php:8.1-apache

ENV SIMPLESAMLPHP_VERSION=1.19.5
ENV SIMPLESAMLPHP_SHA256=ab1a71a4eed2c086774829d47ce72137e4d1a7bd6b8a2535b4a1ddfd6e7da51f
ENV COMPOSER_VERSION=2.3.7

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
    # Add additional modules.
    composer require --ignore-platform-reqs bradjonesllc/simplesamlphp-module-openidconnect dev-master

RUN set -eux; \
    cd /var/simplesamlphp; \
    mv composer.json composer.old.json; \
    cat composer.old.json | \
      jq '.repositories |= . + [{"type": "vcs", "url": "https://github.com/blindern/simplesamlphp-module-fbs"}]' \
      >composer.json; \
    composer require --ignore-platform-reqs blindern/simplesamlphp-module-fbs "dev-master#d9f9d00"

COPY site.conf /etc/apache2/sites-enabled/000-default.conf

EXPOSE 80
