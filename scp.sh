#!/bin/bash

set -e

script="${BASH_SOURCE[0]}"
scriptdir=$(dirname "$script")
cd "$scriptdir"

find -not -perm u=rwx,g=rx,o=rx -a -not -perm u=rw,g=r,o=r

rsync -rtv \
	--chmod=a+rw,g+rw,o+r \
	--exclude=*.sh \
	--exclude=backup \
	* \
	igelhilfe-ludwigshafen.de:/var/www/vhosts/igelhilfe-ludwigshafen.de/httpdocs/map/
