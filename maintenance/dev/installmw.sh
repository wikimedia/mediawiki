#!/bin/bash

if [ "x$BASH_SOURCE" == "x" ]; then echo '$BASH_SOURCE not set'; exit 1; fi
DEV=$(cd -P "$(dirname "${BASH_SOURCE[0]}" )" && pwd)

if [ -d "$DEV/php" -a -x "$DEV/php/bin/php" ]; then
	PHP="$DEV/php/bin/php"
elif [ -d "$HOME/.mwphp" -a -x "$HOME/.mwphp/bin/php" ]; then
	PHP="$HOME/.mwphp/bin/php"
else
	echo "Local copy of PHP is not installed"
	echo 1
fi

set -e

PORT=4881

cd "$DEV/../../"; # $IP

mkdir -p "$DEV/data"
"$PHP" maintenance/install.php --server="http://localhost:$PORT" --scriptpath="" --dbtype=sqlite --dbpath="$DEV/data" --pass=admin "Trunk Test" "$USER"
echo ""
echo "Development wiki created with admin user $USER and password 'admin'."
echo ""
