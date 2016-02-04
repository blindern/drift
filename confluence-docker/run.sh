#!/bin/bash

docker run \
  --name blindern-confluence \
  -d --restart=always \
  -v /var/atlassian/confluence:/var/atlassian/confluence \
  -p 8090:8090 \
  blindern/confluence

