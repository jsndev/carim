#!/bin/bash

usage() {
	echo "$0 [mysql_parameters] <SQL files...>" >&2
	echo "    Restores MySQL dump files as a one-db-per-file basis."
	echo "    Uses the filename as the database name, stripping off .sql and .gz if present."
	echo "    If filename ends in .gz then file is assumed to be gzip compressed."
	echo
	echo "    For example all of the following filenames map to the database name mydb"
	echo "       mydb  mydb.gz  mydb.sql  mydb.sql.gz  /foo/mydb.sql"
	exit 1
}

ARGS=()
FILES=()
arg() {
	ARGS=("${ARGS[@]}" "$1")
}
file() {
	FILES=("${FILES[@]}" "$1")
}

[[ $# == 0 ]] && usage

# Slightly modified getopts alternative
while [[ $# != 0 ]] ; do
	case $1 in
		--)
			break
		;;
		-\?)
			usage
		;;
		-[uphS]) 
			arg $1
			arg $2
			shift
		;;
		-*)
			arg $1
		;;
		*)
			file $1
		;;
	esac
	shift
done

for FILE in "${FILES[@]}" ; do
	if [[ ! -f $FILE ]] ; then
		echo "Skipping '$FILE' (not a file)"
		continue
	fi
	NAME=${FILE##*/}
	if [[ $FILE == *.[gG][zZ] ]] ; then
		NAME=${NAME%.[gG][zZ]}
		DB=${NAME%.[sS][qQ][lL]}
		echo "$DB << $FILE"
		mysql "${ARGS[@]}" -e "create database if not exists \`$DB\`;"
		mysql $DB "${ARGS[@]}" < <(gunzip -c "$FILE")
	else
		DB=${NAME%.[sS][qQ][lL]}
		echo "$DB << $FILE"
		mysql "${ARGS[@]}" -e "create database if not exists \`$DB\`;"
		mysql $DB "${ARGS[@]}" < "$FILE"
	fi
done
