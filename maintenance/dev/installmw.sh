#!/bin/bash

if [ "x$BASH_SOURCE" == "x" ]; then echo '$BASH_SOURCE not set'; exit 1; fi
DEV=$(cd -P "$(dirname "${BASH_SOURCE[0]}" )" && pwd)

. "$DEV/includes/require-php.sh"

set -e

PORT=4881

cd "$DEV/../../"; # $IP

mkdir -p "$DEV/data"
"$PHP" maintenance/install.php --server="http://localhost:$PORT" --scriptpath="" --dbtype=sqlite --dbpath="$DEV/data" --pass=admin "Trunk Test" "$USER"
echo ""
echo "Development wiki created with admin user $USER and password 'admin'."
echo ""
