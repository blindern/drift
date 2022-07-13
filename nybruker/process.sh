#!/bin/bash
set -eu -o pipefail

# TODO: kunne oppdatere navn på en bruker

function addchange() {
	if [ "$FIRST" -eq 0 ]; then
		CHANGESET="$CHANGESET
-"
	fi
	FIRST=0
	CHANGESET="$CHANGESET
replace: $1
$1: $2"
}

function finished() {
	# slett cache på printerserver
	#ssh root@p.foreningenbs.no sss_cache -UG

	# fjern cache på bruker-APIet
	curl \
                -f \
		-X POST \
		-H "Authorization: Bearer $(cat /data/storage-1/users-api-key)" \
		https://foreningenbs.no/users-api/invalidate-cache
}

if [[ "$(id -u)" != "0" ]]; then
	echo "Dette scriptet må kjøre som root!"
	exit
fi


if [[ "${1:-}" == "" ]]; then
	echo "Mangler navn på script med variabler"
	echo "Kjører kun tømming av cache"
	finished
	exit
fi


# source variabler fra script
. /data/intern-backend-data/user-requests/$1

echo "Foreningsbruker:"
echo "- Navn: $FIRSTNAME $LASTNAME"
echo "- Brukernavn: $USERNAME"
echo "- E-post: $MAIL"
echo "- Telefon: $PHONE"
echo

CHANGESET="changetype: modify"
FIRST=1


EMAILEXISTS=$(ldapsearch -x -LLL -H ldap://ldap-master.zt.foreningenbs.no/ -b ou=Users,dc=foreningenbs,dc=no "(&(mail=$MAIL))" mail | wc -l)
USEREXISTS=$(ldapsearch -x -LLL -H ldap://ldap-master.zt.foreningenbs.no/ -b ou=Users,dc=foreningenbs,dc=no "(&(uid=$USERNAME))" uid | wc -l)

if [ $EMAILEXISTS -gt 0 ] && [ $USEREXISTS -eq 0 ]; then
	echo "E-postadressen er allerede registrert på en annen bruker:"
	ldapsearch -x -LLL -H ldap://ldap-master.zt.foreningenbs.no/ -b ou=Users,dc=foreningenbs,dc=no "(&(mail=$MAIL))" mail
	echo "En e-postadresse kan ikke tilhøre flere brukere!"
	exit 1
fi

if [ $USEREXISTS -ne 0 ]; then
	DATA=$(ldapfinger "$USERNAME")

	echo "Bruker finnes fra før!"
	echo "$DATA" | grep "^cn:"
	echo

	OLD_MAIL=$(echo "$DATA" | grep "^mail:" | awk '{print $2}' | head -n 1)
	if [[ "$OLD_MAIL" == "" ]] || [[ "$OLD_MAIL" != "$MAIL" ]]; then
		echo "Oppdatere e-post fra $OLD_MAIL til $MAIL?"
		select yn in "Ja" "Nei"; do
			if [[ "$yn" == "Ja" ]]; then
				addchange "mail" "$MAIL"
			fi
			break
		done
	fi

	OLD_PHONE=$(echo "$DATA" | grep "^mobile:" || true | awk '{print $2}' | head -n 1)
	if [[ "$PHONE" != "" && "$OLD_PHONE" != "$PHONE" ]]; then

		echo "Oppdatere telefon fra $OLD_PHONE til $PHONE?"
		select yn in "Ja" "Nei"; do
			if [[ "$yn" == "Ja" ]]; then
				addchange "mobile" "$PHONE"
			fi
			break
		done
	fi

	OLD_FIRSTNAME=$(echo "$DATA" | grep "^givenName:" | cut -d" " -f2- | head -n 1)
	OLD_LASTNAME=$(echo "$DATA" | grep "^sn:" | cut -d" " -f2- | head -n 1)
	if [[ ("$FIRSTNAME" != "" && "$LASTNAME" != "") && ( "$OLD_FIRSTNAME" != "$FIRSTNAME" || "$OLD_LASTNAME" != "$LASTNAME" ) ]]; then

		echo "Oppdatere navn fra $OLD_FIRSTNAME $OLD_LASTNAME til $FIRSTNAME $LASTNAME?"
		select yn in "Ja" "Nei"; do
			if [[ "$yn" == "Ja" ]]; then
				addchange "givenName" "$FIRSTNAME"
				addchange "sn" "$LASTNAME"
				addchange "displayName" "$FIRSTNAME $LASTNAME"
				addchange "cn" "$FIRSTNAME $LASTNAME"
			fi
			break
		done
	fi

	echo
	echo "Skal passord oppdateres?"
	select yn in "Ja" "Nei"; do
		if [[ "$yn" == "Ja" ]]; then
			addchange "userPassword" "$PASS"
			addchange "sambaNTPassword" "$NTPASS"
		fi
		break
	done
	echo

	if [ "$FIRST" -eq 1 ]; then
		echo "Ingen endringer.."
		exit
	fi

	echo "Forslag til endringer:"
	echo "$CHANGESET"

	echo
	echo "Lagre endringer?"
	select yn in "Ja" "Nei"; do
		if [[ "$yn" != "Ja" ]]; then
			exit
		fi
		break
	done


	echo "$CHANGESET" | ldapmodifyuser "$USERNAME"


else
	echo "Skal vi legge til ny bruker?"
	select yn in "Ja" "Nei"; do
		if [[ "$yn" != "Ja" ]]; then
			exit
		fi
		break
	done

	echo "Hvilken gruppe?"
	select GROUP in "beboer" "utflyttet"; do
		if [[ "$GROUP" == "beboer" ]] || [[ "$GROUP" == "utflyttet" ]]; then
			break
		fi
	done

	smbldap-useradd -a -N "$FIRSTNAME" -S "$LASTNAME" "$USERNAME"
	ldapaddusertogroup "$USERNAME" "$GROUP"

	addchange "mail" "$MAIL"
	if [[ "$PHONE" != "" ]]; then
		addchange "mobile" "$PHONE"
	fi
	addchange "userPassword" "$PASS"
	addchange "sambaNTPassword" "$NTPASS"

	echo "$CHANGESET" | ldapmodifyuser "$USERNAME"

fi

finished
