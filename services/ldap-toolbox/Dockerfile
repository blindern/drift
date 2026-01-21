FROM ubuntu:24.04@sha256:cd1dba651b3080c3686ecf4e3c4220f026b521fb76978881737d24f200828b2b

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
