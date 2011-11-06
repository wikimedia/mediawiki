#!/bin/bash

if [[ "x$BASH_SOURCE" == "x" ]]; then echo '$BASH_SOURCE not set'; exit 1; fi
DEV=$(cd -P "$(dirname "${BASH_SOURCE[0]}" )" && pwd)

PORT=4881

echo "Starting up MediaWiki at http://localhost:$PORT/"
echo ""

cd $DEV/../../; # $IP
$DEV/php/bin/php -S localhost:$PORT
