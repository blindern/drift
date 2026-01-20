FROM php:8.3-apache@sha256:dece6673f3e0a4fdf47f797f85403931c72481d56e4086566ee5169e3eeb7011

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

COPY --from=composer:2@sha256:ce78434db8122b0c3b0ec2056da0072551780d60e864943008c3f5a785db0900 /usr/bin/composer /usr/local/bin/composer

RUN set -eux; \
    mkdir /var/simplesamlphp; \
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
    composer clear-cache

COPY site.conf /etc/apache2/sites-enabled/000-default.conf

EXPOSE 80
