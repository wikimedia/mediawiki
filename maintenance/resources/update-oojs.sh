#!/usr/bin/env bash

if [ -n "$2" ]
then
	# Too many parameters
	echo >&2 "Usage: $0 [<version>]"
	exit 1
fi

REPO_DIR=$(cd $(dirname $0)/../..; pwd) # Root dir of the git repo working tree
TARGET_DIR=resources/lib/oojs # Destination relative to the root of the repo
NPM_DIR=`mktemp -d 2>/dev/null || mktemp -d -t 'update-oojs'` # e.g. /tmp/update-oojs.rI0I5Vir

# Prepare working tree
cd $REPO_DIR &&
git reset $TARGET_DIR && git checkout $TARGET_DIR && git fetch origin &&
git checkout -B upstream-oojs origin/master || exit 1

# Fetch upstream version
cd $NPM_DIR
if [ -n "$1" ]
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
rsync --recursive --delete --force ./node_modules/oojs/dist $REPO_DIR/$TARGET_DIR || exit 1

# Clean up temporary area
rm -rf $NPM_DIR

# Generate commit
cd $REPO_DIR || exit 1

COMMITMSG=$(cat <<END
Update OOjs to v$OOJS_VERSION

Release notes:
 https://git.wikimedia.org/blob/oojs%2Fcore.git/v$OOJS_VERSION/History.md
END
)

# Stage deletion, modification and creation of files. Then commit.
git add --update $TARGET_DIR && git add $TARGET_DIR && git commit -m "$COMMITMSG" || exit 1
