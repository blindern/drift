#!/bin/sh
set -eu

exec /usr/sbin/slapd \
  -h "ldap:/// ldaps:/// ldapi:///" \
  -u ldap \
  -g ldap \
  -d $LDAP_LOG_LEVEL \
  -F /etc/ldap/slapd.d
