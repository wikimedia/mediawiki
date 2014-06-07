#!/usr/bin/env bash

if [ "$2" != "" ]
then
	echo >&2 "Usage: $0 [<version>]"
	exit 1
fi

MW_DIR=$(cd $(dirname $0)/../..; pwd) # e.g. mediawiki-core/
NPM_DIR=`mktemp -d 2>/dev/null || mktemp -d -t 'mw-update-oojs'` # e.g. /tmp/mw-update-oojs.rI0I5Vir

# Prepare MediaWiki working copy
cd $MW_DIR
git reset resources/lib/oojs/ && git checkout resources/lib/oojs/ && git fetch origin || exit 1

git checkout -B upstream-oojs origin/master || exit 1

# Fetch upstream version
cd $NPM_DIR
if [ "$1" != "" ]
then
	npm install oojs@$1 || exit 1
else
	npm install oojs || exit 1
fi

OOJS_VERSION=$(node -e 'console.log(JSON.parse(require("fs").readFileSync("./node_modules/oojs/package.json")).version);')
if [ "$OOJS_VERSION" == "" ]
then
	echo 'Could not find OOjs version'
	exit 1
fi

# Copy file(s)
mv ./node_modules/oojs/dist/* $MW_DIR/resources/lib/oojs/ || exit 1

# Generate commit
cd $MW_DIR || exit 1

# Clean up temporary area
rm -rf $NPM_DIR

COMMITMSG=$(cat <<END
Update OOjs to v$OOJS_VERSION

Release notes:
 https://git.wikimedia.org/blob/oojs%2Fcore.git/v$OOJS_VERSION/History.md
END
)

git commit resources/lib/oojs/ -m "$COMMITMSG" || exit 1
