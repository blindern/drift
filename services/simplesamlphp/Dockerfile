FROM php:8.5-apache@sha256:705b53b7eb799eba54c2a0f1ab74993330388b8b9a2c5270f0dda78ecb3b29ca

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

COPY --from=composer:2@sha256:4d71c3c2109c61d5415544264b59ad4087e4c5b7244481723664138fd36d5040 /usr/bin/composer /usr/local/bin/composer

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
