#!/usr/bin/env bash

# This script generates a commit that updates our distribution copy of OOjs UI

if [ -z "$1" ]
then
	# Missing required parameter
	echo >&2 "Usage: $0 path/to/repo/for/oojs-ui"
	exit 1
fi

TARGET_REPO=$(cd $(dirname $0)/../..; pwd)
TARGET_DIR=resources/lib/oojs-ui
UI_REPO=$1

function oojsuihash() {
	grep "OOjs UI v" $TARGET_REPO/$TARGET_DIR/oojs-ui.js \
		| head -n 1 \
		| grep -Eo '\([a-z0-9]+\)' \
		| sed 's/^(//' \
		| sed 's/)$//'
}

function oojsuitag() {
	grep "OOjs UI v" $TARGET_REPO/$TARGET_DIR/oojs-ui.js \
		| head -n 1 \
		| grep -Eo '\bv[0-9a-z.-]+\b'
}

function oojsuiversion() {
	grep "OOjs UI v" $TARGET_REPO/$TARGET_DIR/oojs-ui.js \
		| head -n 1 \
		| grep -Eo '\bv[0-9a-z.-]+\b.*$'
}

# Prepare working tree
cd $TARGET_REPO &&
git reset $TARGET_DIR && git checkout $TARGET_DIR && git fetch origin &&
git checkout -B upstream-oojsui origin/master || exit 1

cd $UI_REPO || exit 1

# Read the old version and check for changes
OLDHASH=$(oojsuihash)
if [ -z "$OLDHASH" ]
then
	OLDTAG=$(oojsuitag)
fi
if [ "$OLDHASH" == "" ]
then
	OLDHASH=$(git rev-parse $OLDTAG)
	if [ $? != 0 ]
	then
		echo Could not find OOjs UI version
		cd -
		exit 1
	fi
fi
if [ "$(git rev-parse $OLDHASH)" == "$(git rev-parse HEAD)" ]
then
	echo "No changes (already at $OLDHASH)"
	cd -
	exit 0
fi

# Build the distribution via npm install's implicit grunt build
npm install || exit 1

# Get the list of changes
NEWCHANGES=$(git log $OLDHASH.. --oneline --no-merges --reverse --color=never)
NEWCHANGESDISPLAY=$(git log $OLDHASH.. --oneline --no-merges --reverse --color=always)

# Copy files
# - Exclude the default non-svg stylesheet
rsync --recursive --delete --force --exclude 'oojs-ui.css' ./dist/ $TARGET_REPO/$TARGET_DIR || exit 1

# Read the new version
NEWVERSION=$(oojsuiversion)

# Generate commit
cd $TARGET_REPO
COMMITMSG=$(cat <<END
Update OOjs UI to $NEWVERSION

New changes:
$NEWCHANGES
END
)
git add -u $TARGET_DIR && git add $TARGET_DIR && git commit -m "$COMMITMSG"
cat >&2 <<END


Created commit with changes:
$NEWCHANGESDISPLAY
END
