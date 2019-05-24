#!/bin/bash
#
# This script removes the user from 'beboer'-group,
# and all other groups specified on command line, and
# adds the user to 'utflyttet'-group
set -e

user=$1
if [ "$user" == "" ];
then
	echo "Missing user!"
	exit 1
fi

ldapdeleteuserfromgroup $user beboer
for x in ${*:2}
do
	ldapdeleteuserfromgroup $user $x
done

ldapaddusertogroup $user utflyttet
