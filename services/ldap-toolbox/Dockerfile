FROM ubuntu:26.04@sha256:f144425ff09be612d6d9ad965196e9cdc23dae1f42110a8a11a3e9a8198759f7

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
