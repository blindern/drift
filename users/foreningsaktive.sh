#!/bin/bash
#
# This script makes all users that is indirectly
# a member of the group 'foreningsaktive'
# a direct member of the group

# It will post a "error" for all users that is already
# a direct member of the group, while it will correctly
# add those who are not
set -e -o pipefail

getent group foreningsaktive | awk -F: '{print $4}' | tr ',' '\n' | xargs -iX ldapaddusertogroup X foreningsaktive
