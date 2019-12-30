FROM php:7.4-apache

ENV SIMPLESAMLPHP_VERSION=1.18.3
ENV SIMPLESAMLPHP_SHA256=c6cacf821ae689de6547092c5d0c854e787bfcda716096b1ecf39ad3b3882500
ENV COMPOSER_VERSION=1.9.1

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
      git \
      jq \
      unzip \
      wget \
      zip \
    ; \
    rm -rf /var/lib/apt/lists/*; \
    \
    # Set up composer.
    echo "$(wget -q -O- https://composer.github.io/installer.sig)  composer-setup.php" >composer-setup.php.sha384; \
    wget -O composer-setup.php https://getcomposer.org/installer; \
    sha384sum -c composer-setup.php.sha384; \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer --quiet; \
    rm composer-setup.php; \
    rm composer-setup.php.sha384; \
    \
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
    composer require bradjonesllc/simplesamlphp-module-openidconnect dev-master

# Use a separate RUN for this so we can keep cache above.
RUN set -eux; \
    cd /var/simplesamlphp; \
    mv composer.json composer.old.json; \
    cat composer.old.json | \
      jq '.repositories |= . + [{"type": "vcs", "url": "https://github.com/blindern/simplesamlphp-module-fbs"}]' \
      >composer.json; \
    composer require blindern/simplesamlphp-module-fbs "dev-master#de3a43a"

COPY site.conf /etc/apache2/sites-enabled/000-default.conf

EXPOSE 80
