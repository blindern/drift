FROM alpine:3.21

RUN set -eux; \
    # Create the user first so we control the uid/gid.
    addgroup -g 1000 ldap; \
    adduser -u 1000 -D -G ldap ldap; \
    apk add --no-cache \
      bash \
      db-utils \
      openldap \
      openldap-back-mdb \
      openldap-clients \
      openldap-overlay-all \
      openssl \
    ; \
    mkdir -p /var/run/slapd; \
    chown ldap:ldap /var/run/slapd; \
    # Compatibility to previous setup.
    ln -s /usr/lib/openldap /usr/lib/ldap; \
    # Location for ldapi socket.
    mkdir -p /var/lib/openldap/run; \
    chown ldap:ldap /var/lib/openldap/run; \
    # Verify expected/tested version.
    slapd -V; \
    slapd -V 2>&1 | grep -q 'slapd 2.6' || (echo "Unexpected slapd version" && exit 1)

COPY entrypoint.sh /entrypoint.sh
COPY run.sh /run.sh

# https://www.openldap.org/doc/admin24/slapdconfig.html
ENV LDAP_LOG_LEVEL=256

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/run.sh"]

EXPOSE 389 636
