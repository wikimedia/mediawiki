#!/bin/bash

if [[ "x$BASH_SOURCE" == "x" ]]; then echo '$BASH_SOURCE not set'; exit 1; fi
DEV=$(cd -P "$(dirname "${BASH_SOURCE[0]}" )" && pwd)

set -e

PORT=4881

cd "$DEV/../../"; # $IP

mkdir "$DEV/data"
"$DEV/php/bin/php" maintenance/install.php --server="http://localhost:$PORT" --scriptpath="" --dbtype=sqlite --dbpath="$DEV/data" --pass=admin "Trunk Test" "$USER"
echo ""
echo "Development wiki created with admin user $USER and password 'admin'."
echo ""
