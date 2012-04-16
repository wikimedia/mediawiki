#!/bin/bash

if [ "x$BASH_SOURCE" == "x" ]; then echo '$BASH_SOURCE not set'; exit 1; fi
DEV=$(cd -P "$(dirname "${BASH_SOURCE[0]}" )" && pwd)

. "$DEV/includes/require-php.sh"

PORT=4881

echo "Starting up MediaWiki at http://localhost:$PORT/"
echo ""

cd "$DEV/../../"; # $IP
"$PHP" -S "localhost:$PORT" "$DEV/includes/router.php"
