#!/bin/bash

script="${BASH_SOURCE[0]}"
scriptdir=$(dirname "$script")
cd "$scriptdir"

[ "$(uname -s)" = "Linux" ] || protocol=--protocol=TCP

mysql $protocol \
	--user=igelhilfe \
	--password="$(getpass machine=mysql://localhost login=igelhilfe)" \
	--default-character-set=utf8 \
	< hedgehogs.sql
