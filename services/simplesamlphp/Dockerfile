FROM php:8.4-apache@sha256:f1cba0924d62fd348df37bb0b8b699158b1fb03c300831d192b7471b14f99dd4

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
      git \
      jq \
      libpq-dev \
      unzip \
      wget \
      zip \
    ; \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_pgsql

RUN pecl install opentelemetry \
    && pecl install protobuf \
    && docker-php-ext-enable opentelemetry protobuf

RUN echo 'auto_prepend_file=/var/simplesamlphp/otel-prepend.php' > "$PHP_INI_DIR/conf.d/otel-prepend.ini" \
    && mkdir -p /var/simplesamlphp \
    && touch /var/simplesamlphp/otel-prepend.php

COPY --from=composer:2@sha256:7725eb4545c438629ae8bde3ef0bb9a5038ef566126ad878442a69007242d267 /usr/bin/composer /usr/local/bin/composer

RUN set -eux; \
    chown www-data:www-data /var/simplesamlphp /var/www

# renovate: datasource=github-tags depName=simplesamlphp/simplesamlphp
ENV SIMPLESAMLPHP_VERSION=v2.5.2

# renovate: datasource=git-refs depName=https://github.com/blindern/simplesamlphp-module-authoauth2.git tag=master
ENV AUTHOAUTH2_COMMIT=6e0227ddbad64a612dea84cd2073a9932563d5b2

# renovate: datasource=git-refs depName=https://github.com/blindern/simplesamlphp-module-fbs.git tag=master
ENV FBS_COMMIT=21cb18bad5862d63ec304158012d966778f455d0

USER www-data

SHELL ["/bin/bash", "-o", "pipefail", "-c"]
RUN set -eux; \
    # Install SimpleSAMLphp.
    # See https://simplesamlphp.org/docs/stable/simplesamlphp-install
    cd /var/simplesamlphp; \
    curl -fSL "https://github.com/simplesamlphp/simplesamlphp/releases/download/$SIMPLESAMLPHP_VERSION/simplesamlphp-${SIMPLESAMLPHP_VERSION:1}-slim.tar.gz" \
      -o simplesamlphp.tar.gz; \
    tar --strip-components=1 -zxf simplesamlphp.tar.gz; \
    rm simplesamlphp.tar.gz; \
    \
    composer install --ignore-platform-reqs; \
    mv composer.json composer.old.json; \
    cat composer.old.json | \
      jq '.repositories |= . + [\
        {"type": "git", "url": "https://github.com/blindern/simplesamlphp-module-fbs"},\
        {"type": "git", "url": "https://github.com/blindern/simplesamlphp-module-authoauth2"}]' \
      >composer.json; \
    composer require --ignore-platform-reqs --with-all-dependencies cirrusidentity/simplesamlphp-module-authoauth2 "dev-master#${AUTHOAUTH2_COMMIT}"; \
    composer require --ignore-platform-reqs --with-all-dependencies blindern/simplesamlphp-module-fbs "dev-master#${FBS_COMMIT}"; \
    composer require --ignore-platform-reqs --with-all-dependencies \
      open-telemetry/sdk \
      open-telemetry/exporter-otlp \
      open-telemetry/opentelemetry-auto-curl \
      open-telemetry/opentelemetry-auto-guzzle \
      open-telemetry/opentelemetry-auto-pdo \
      open-telemetry/opentelemetry-auto-symfony; \
    composer clear-cache

COPY --chown=www-data:www-data otel-prepend.php /var/simplesamlphp/otel-prepend.php
COPY site.conf /etc/apache2/sites-enabled/000-default.conf

ENV OTEL_PHP_AUTOLOAD_ENABLED=true
ENV OTEL_SERVICE_NAME=simplesamlphp
ENV OTEL_TRACES_EXPORTER=otlp
ENV OTEL_EXPORTER_OTLP_PROTOCOL=http/protobuf
ENV OTEL_EXPORTER_OTLP_ENDPOINT=http://signoz-otel-collector.zt.foreningenbs.no:4318
ENV OTEL_PROPAGATORS=baggage,tracecontext

EXPOSE 80
