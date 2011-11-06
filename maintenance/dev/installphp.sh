#!/bin/bash

if [[ "x$BASH_SOURCE" == "x" ]]; then echo '$BASH_SOURCE not set'; exit 1; fi
DEV=$(cd -P "$(dirname "${BASH_SOURCE[0]}" )" && pwd)

set -e # DO NOT USE PIPES unless this is rewritten

if [ -d $DEV/php ]; then
	echo "PHP is already installed"
	exit 1
fi

TAR=php5.4-latest.tar.gz
PHPURL=http://snaps.php.net/$TAR

cd $DEV

# Some debain-like systems bundle wget but not curl, some other systems
# like os x bundle curl but not wget... use whatever is available
echo -n "Downloading PHP 5.4"
if command -v wget &>/dev/null; then
	echo "- using wget"
	wget $PHPURL
elif command -v curl &>/dev/null; then
	echo "- using curl"
	curl -O $PHPURL
else
	echo "- aborting"
	echo "Could not find curl or wget." >&2;
	exit 1;
fi

echo "Extracting php 5.4"
tar -xzf $TAR

cd php5.4-*/

echo "Configuring and installing php 5.4 in $IP/maintenance/dev/php/"
./configure --prefix=$DEV/php/
make
make install
