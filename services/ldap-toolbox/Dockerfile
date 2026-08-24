FROM ubuntu:26.04@sha256:2260313b31c8c011cd2eebe728008efac1b3982be73eb71348ea2648d2c0e09b

ARG DEBIAN_FRONTEND=noninteractive
ENV LANG=C.UTF-8

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
