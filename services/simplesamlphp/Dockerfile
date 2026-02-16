FROM php:8.3-apache@sha256:b1f4182d6840ed0dc9b91aa9e1cc11836a63226d350ee620b34d3be486a0327f

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

COPY --from=composer:2@sha256:f0809732b2188154b3faa8e44ab900595acb0b09cd0aa6c34e798efe4ebc9021 /usr/bin/composer /usr/local/bin/composer

RUN set -eux; \
    chown www-data:www-data /var/simplesamlphp /var/www

# renovate: datasource=github-tags depName=simplesamlphp/simplesamlphp
ENV SIMPLESAMLPHP_VERSION=v2.4.4

# renovate: datasource=git-refs depName=https://github.com/blindern/simplesamlphp-module-authoauth2.git tag=master
ENV AUTHOAUTH2_COMMIT=b4bd0163ed99f80b9d642214b08bf5d6b73c182b

# renovate: datasource=git-refs depName=https://github.com/blindern/simplesamlphp-module-fbs.git tag=master
ENV FBS_COMMIT=92166d984f2eab0d0c0adf37ea77bf1c511af116

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
    composer require --ignore-platform-reqs cirrusidentity/simplesamlphp-module-authoauth2 "dev-ssp2#${AUTHOAUTH2_COMMIT}"; \
    composer require --ignore-platform-reqs blindern/simplesamlphp-module-fbs "dev-master#${FBS_COMMIT}"; \
    composer require --ignore-platform-reqs \
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
