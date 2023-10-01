FROM debian:12

# renovate: datasource=git-refs depName=https://github.com/DanRohde/webdavcgi.git tag=master
ENV WEBDAVCGI_COMMIT=4b8bf48d7ffb8efee37a89b9bafb38d6c4247b17

RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
      acl \
      apache2 \
      binutils \
      ca-certificates \
      ldap-utils \
      libapache2-mod-auth-mellon \
      libnss-sss \
      locales \
      procps \
      sssd \
      sssd-tools \
      supervisor \
      unzip \
      vim \
      wget \
      \
      # dependencies to build webdavcgi wrapper
      gcc \
      libc6-dev \
      \
      # webdavcgi dependencies
      # listed at https://danrohde.github.io/webdavcgi/doc.html#installation
      ghostscript \
      libcgi-pm-perl \
      libdbd-sqlite3-perl \
      libtimedate-perl \
      libuuid-tiny-perl \
      libxml-simple-perl \
      libquota-perl \
      libarchive-zip-perl \
      libgraphics-magick-perl \
      libfile-copy-link-perl \
      libjson-perl \
      libdatetime-format-human-duration-perl \
      libimage-exiftool-perl \
      liblist-moreutils-perl \
    ; \
    rm -rf /var/lib/apt/lists/*; \
    a2enmod \
      authnz_ldap \
      cgid \
      ldap \
      rewrite \
      remoteip \
    ; \
    sed -i -e 's/# nb_NO.UTF-8 UTF-8/nb_NO.UTF-8 UTF-8/' /etc/locale.gen; \
    sed -i -e 's/# en_US.UTF-8 UTF-8/en_US.UTF-8 UTF-8/' /etc/locale.gen; \
    locale-gen; \
    rm /etc/apache2/sites-enabled/*

ENV LANG=nb_NO.UTF-8

RUN set -eux; \
    wget https://github.com/DanRohde/webdavcgi/archive/$WEBDAVCGI_COMMIT.zip -O /webdavcgi.zip; \
    unzip /webdavcgi.zip -d /; \
    mv /webdavcgi-$WEBDAVCGI_COMMIT /opt/webdavcgi; \
    rm /webdavcgi.zip; \
    cd /opt/webdavcgi; \
    ./install.sh

COPY container/*.sh /
COPY container/supervisord.conf container/webdav.conf /etc/
COPY container/sssd.conf /etc/sssd/sssd.conf

COPY container/site.conf /etc/apache2/sites-enabled/site.conf
COPY container/apache-extra.conf /etc/apache2/conf-enabled/extra.conf

CMD ["/run.sh"]
