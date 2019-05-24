#!/bin/bash
set -e

user=$1
if [ -z $user ]; then
	echo "Missing user!"
	exit 1
fi

ldapdeleteuserfromgroup $user utflyttet
ldapaddusertogroup $user beboer
