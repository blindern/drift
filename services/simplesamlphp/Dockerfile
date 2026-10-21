FROM php:8.5.12-apache@sha256:4d1f268dd3b670bd003861c5385cb2f8ba7196dbc88ee84dcae47270c116f242

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

# Build the same extensions from pinned upstream sources without depending on PECL downloads.
# renovate: datasource=github-tags depName=open-telemetry/opentelemetry-php-instrumentation tag=1.4.1
ENV OPENTELEMETRY_EXT_COMMIT=7e501acaabb7839c52da8d782570a0cac1428c0f

# Protobuf upstream v36.2 provides PHP extension 5.36.2.
# renovate: datasource=github-tags depName=protocolbuffers/protobuf tag=v36.2
ENV PROTOBUF_EXT_COMMIT=2c74169b34066ceb8ddb6b882fcb3fb32d737a55

RUN set -eux; \
    mkdir -p /tmp/opentelemetry /tmp/protobuf; \
    curl -fSL --retry 3 \
      "https://github.com/open-telemetry/opentelemetry-php-instrumentation/archive/$OPENTELEMETRY_EXT_COMMIT.tar.gz" \
      -o /tmp/opentelemetry.tar.gz; \
    curl -fSL --retry 3 \
      "https://github.com/protocolbuffers/protobuf/archive/$PROTOBUF_EXT_COMMIT.tar.gz" \
      -o /tmp/protobuf.tar.gz; \
    tar -xzf /tmp/opentelemetry.tar.gz -C /tmp/opentelemetry --strip-components=1; \
    tar -xzf /tmp/protobuf.tar.gz -C /tmp/protobuf --strip-components=1; \
    # Match the layout of the Protobuf PECL source package.
    mkdir -p /tmp/protobuf/php/ext/google/protobuf/third_party; \
    cp -R /tmp/protobuf/third_party/utf8_range /tmp/protobuf/php/ext/google/protobuf/third_party/; \
    docker-php-ext-install -j"$(nproc)" \
      /tmp/opentelemetry/ext \
      /tmp/protobuf/php/ext/google/protobuf; \
    php -r 'exit(extension_loaded("opentelemetry") && extension_loaded("protobuf") ? 0 : 1);'; \
    rm -rf /tmp/opentelemetry /tmp/protobuf /tmp/opentelemetry.tar.gz /tmp/protobuf.tar.gz

RUN echo 'auto_prepend_file=/var/simplesamlphp/otel-prepend.php' > "$PHP_INI_DIR/conf.d/otel-prepend.ini"

COPY --from=composer:2@sha256:af98f42dfff7c68ba8d53c2164fd9fde1087b7d449514baa38c418b1f6bc4bac /usr/bin/composer /usr/local/bin/composer

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
