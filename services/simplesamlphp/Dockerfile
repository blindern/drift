FROM php:8.5-apache@sha256:eacc0d98992683cb46e4f8f44b2418a0323855dc8b59d32dc54f7a9b90a966dd

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
      git \
      libpq-dev \
      unzip \
      zip \
    ; \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_pgsql

# renovate: datasource=custom.pecl depName=opentelemetry
ENV OPENTELEMETRY_EXT_VERSION=1.2.1

# renovate: datasource=custom.pecl depName=protobuf
ENV PROTOBUF_EXT_VERSION=5.35.1

RUN pecl install "opentelemetry-$OPENTELEMETRY_EXT_VERSION" "protobuf-$PROTOBUF_EXT_VERSION" \
    && docker-php-ext-enable opentelemetry protobuf

RUN echo 'auto_prepend_file=/var/simplesamlphp/otel-prepend.php' > "$PHP_INI_DIR/conf.d/otel-prepend.ini"

COPY --from=composer:2@sha256:5946476338742b200bb9ff88f8be56275ddae4b3949c72305cb0dbf10cfcb760 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/simplesamlphp
RUN chown www-data:www-data /var/simplesamlphp /var/www

USER www-data
COPY --chown=www-data:www-data composer.json composer.lock otel-prepend.php ./
RUN set -eux; \
    composer install --ignore-platform-reqs --no-dev --no-interaction; \
    composer clear-cache

COPY site.conf /etc/apache2/sites-enabled/000-default.conf

ENV SIMPLESAMLPHP_CONFIG_DIR=/var/simplesamlphp/config
ENV OTEL_PHP_AUTOLOAD_ENABLED=true
ENV OTEL_SERVICE_NAME=simplesamlphp
ENV OTEL_TRACES_EXPORTER=otlp
ENV OTEL_EXPORTER_OTLP_PROTOCOL=http/protobuf
ENV OTEL_EXPORTER_OTLP_ENDPOINT=http://signoz-otel-collector.zt.foreningenbs.no:4318
ENV OTEL_PROPAGATORS=baggage,tracecontext

EXPOSE 80
