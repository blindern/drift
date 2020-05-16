#!/bin/bash
set -eu

docker-compose up -d --build
docker-compose exec ldap-toolbox bash
