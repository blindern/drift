FROM ubuntu:22.04

RUN set -eux; \
    apt-get update; \
    apt-get install --no-install-recommends -y \
      ca-certificates \
      curl \
      iputils-ping \
      ldapscripts \
      samba \
      smbldap-tools \
      vim \
    ; \
    rm -rf /var/lib/apt/lists/*
