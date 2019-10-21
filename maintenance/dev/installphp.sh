#!/bin/bash

if [ "x$BASH_SOURCE" == "x" ]; then echo '$BASH_SOURCE not set'; exit 1; fi
DEV=$(cd -P "$(dirname "${BASH_SOURCE[0]}" )" && pwd)

set -e # DO NOT USE PIPES unless this is rewritten

. "$DEV/includes/php.sh"

if [ "x$PHP" != "x" -a -x "$PHP" ]; then
	echo "PHP is already installed"
	exit 0
fi

VER=5.6.32
TAR="php-$VER.tar.gz"
PHPURL="https://www.php.net/get/$TAR/from/this/mirror"

cd "$DEV"

echo "Preparing to download and install a local copy of PHP $VER, note that this can take some time to do."
echo "If you wish to avoid re-doing this for future dev installations of MediaWiki we suggest installing php in ~/.mediawiki/php"
echo -n "Install PHP in ~/.mediawiki/php [y/N]: "
read INSTALLINHOME

case "$INSTALLINHOME" in
	[Yy] | [Yy][Ee][Ss] )
		PREFIX="$HOME/.mediawiki/php"
		;;
	*)
		PREFIX="$DEV/php/"
		;;
esac

# Some debian-like systems bundle wget but not curl, some other systems
# like os x bundle curl but not wget... use whatever is available
echo -n "Downloading PHP $VER"
if command -v wget &>/dev/null; then
	echo " - using wget"
	wget -O "$TAR" "$PHPURL"
elif command -v curl &>/dev/null; then
	echo " - using curl"
	curl "$PHPURL" -L -o "$TAR"
else
	echo " - aborting"
	echo "Could not find curl or wget." >&2;
	exit 1;
fi

echo "Extracting php $VER"
tar -xzf "$TAR"

cd "php-$VER/"

echo "Configuring and installing php $VER in $PREFIX"
./configure --prefix="$PREFIX"
make
make install
