#!/bin/bash -eu
set +x

# This copies OOjs into the MW source tree, either from a published
# version or from a local directory
# If --published is passed, it outputs the OOjs version.  Otherwise,
# it builds the dist directory.

if [ -n "${3:-}" ]
then
	# Too many parameters
	echo >&2 "Usage: $0 [--published] <OOjs location to install from>"
	exit 1
fi

PUBLISHED=0
if [ "$1" = "--published" ]
then
	PUBLISHED=1
	shift
fi

REPO_DIR=$(cd "$(dirname $0)/../.."; pwd) # Root dir of the git repo working tree
TARGET_DIR="resources/lib/oojs" # Destination relative to the root of the repo
NPM_DIR=$(mktemp -d 2>/dev/null || mktemp -d -t 'update-oojs') # e.g. /tmp/update-oojs.rI0I5Vir

# Prepare working tree
cd "$REPO_DIR"
git reset -q -- $TARGET_DIR
git checkout -- $TARGET_DIR
git fetch origin

# Fetch upstream version
cd $NPM_DIR
if [ "$PUBLISHED" -eq 0 ]
then
	pushd "$1"
	npm install
	grunt build
	popd
fi
npm install "$1" &>/dev/null

OOJS_VERSION=$(node -e 'console.log(require("./node_modules/oojs/package.json").version);')
if [ "$OOJS_VERSION" == "" ]
then
	echo 'Could not find OOjs version'
	exit 1
fi

# Copy file(s)
rsync --force ./node_modules/oojs/dist/oojs.jquery.js "$REPO_DIR/$TARGET_DIR"

if [ "$PUBLISHED" -eq 1 ]
then
	echo "$OOJS_VERSION"
fi

# Clean up temporary area
rm -rf "$NPM_DIR"
