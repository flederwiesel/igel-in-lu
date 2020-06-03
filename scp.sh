#!/bin/bash

set -e

script="${BASH_SOURCE[0]}"
scriptdir=$(dirname "$script")
cd "$scriptdir"

rsync -rtv \
	--chmod=a+rw,g+rw,o+r \
	--exclude=*.sh \
	--exclude=backup \
	* \
	igelhilfe-ludwigshafen.de:/var/www/vhosts/igelhilfe-ludwigshafen.de/httpdocs/map/
