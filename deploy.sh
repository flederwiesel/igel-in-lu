#!/bin/bash

set -e

script="${BASH_SOURCE[0]}"
scriptdir=$(dirname "$script")
cd "$scriptdir"

[ "$(uname -s)" = "Linux" ] || protocol=--protocol=TCP

while read marker
do
	[ -e "img/$marker.png" ] ||
	{
		echo "img/$marker.png not found." >&2
		exit 1
	}
done < <(
mysql $protocol \
	--user=igelhilfe \
	--password="$(getpass machine=mysql://localhost login=igelhilfe)" \
	--database "igel-in-lu" \
	--default-character-set=utf8 \
	--skip-column-names \
	<<"EOF"
		SELECT DISTINCT CONCAT(`marker1`, '-', `marker2`) AS `marker`
		FROM `hedgehogs`
		ORDER BY `marker`
EOF
)

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
