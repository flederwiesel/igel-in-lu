#!/bin/bash

set -e

script="${BASH_SOURCE[0]}"
scriptdir=$(dirname "$script")
cd "$scriptdir"

rsync -rtv \
	--chmod=a+rw,g+rw,o+r \
	--exclude=.git* \
	--exclude=*.pdn \
	--exclude=*.ppj \
	--exclude=*.ppw \
	--exclude=*.sh \
	--exclude=*.sql \
	--exclude=backup \
	* \
	igelhilfe-ludwigshafen.de:/var/www/vhosts/igelhilfe-ludwigshafen.de/httpdocs/map/

ssh igelhilfe@igelhilfe-ludwigshafen.de \
mysql \
	--user=igelhilfe \
	--password="'$(getpass machine=mysql://localhost login=igelhilfe)'" \
	--default-character-set=utf8 \
	< hedgehogs.sql
