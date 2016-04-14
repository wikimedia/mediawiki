#!/bin/bash -eu

# This copies OOjs UI into the MW source tree, either from a published
# version or from a local directory
# If --published is passed, it outputs the OOjs version.  Otherwise,
# it builds the dist directory.

if [ -n "${3:-}" ]
then
	# Too many parameters
	echo >&2 "Usage: $0 [--published] <OOjs UI location to install from>"
	exit 1
fi

PUBLISHED=0
if [ "$1" = "--published" ]
then
	PUBLISHED=1
	shift
fi

REPO_DIR=$(cd "$(dirname $0)/../.."; pwd) # Root dir of the git repo working tree
TARGET_DIR="resources/lib/oojs-ui" # Destination relative to the root of the repo
NPM_DIR=$(mktemp -d 2>/dev/null || mktemp -d -t 'update-oojs-ui') # e.g. /tmp/update-oojs.rI0I5Vir

# Prepare working tree
cd "$REPO_DIR"
git reset -q composer.json
git checkout composer.json
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

OOJSUI_VERSION=$(node -e 'console.log(require("./node_modules/oojs-ui/package.json").version);')
if [ "$OOJSUI_VERSION" == "" ]
then
	echo 'Could not find OOjs UI version'
	exit 1
fi

# Copy files, picking the necessary ones from source and distribution
rm -r "$REPO_DIR/$TARGET_DIR"
mkdir -p "$REPO_DIR/$TARGET_DIR/i18n"
mkdir -p "$REPO_DIR/$TARGET_DIR/images"
mkdir -p "$REPO_DIR/$TARGET_DIR/themes/mediawiki/images"
mkdir -p "$REPO_DIR/$TARGET_DIR/themes/apex/images"
cp ./node_modules/oojs-ui/dist/oojs-ui-core.js "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-core-{mediawiki,apex}.css "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-widgets.js "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-widgets-{mediawiki,apex}.css "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-toolbars.js "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-toolbars-{mediawiki,apex}.css "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-windows.js "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-windows-{mediawiki,apex}.css "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-{mediawiki,apex}.js "$REPO_DIR/$TARGET_DIR"
cp -R ./node_modules/oojs-ui/dist/i18n "$REPO_DIR/$TARGET_DIR"
cp -R ./node_modules/oojs-ui/dist/images "$REPO_DIR/$TARGET_DIR"
cp -R ./node_modules/oojs-ui/dist/themes/mediawiki/images "$REPO_DIR/$TARGET_DIR/themes/mediawiki"
cp ./node_modules/oojs-ui/src/themes/mediawiki/*.json "$REPO_DIR/$TARGET_DIR/themes/mediawiki"
cp -R ./node_modules/oojs-ui/dist/themes/apex/images "$REPO_DIR/$TARGET_DIR/themes/apex"
cp ./node_modules/oojs-ui/src/themes/apex/*.json "$REPO_DIR/$TARGET_DIR/themes/apex"

if [ "$PUBLISHED" -eq 1 ]
then
	echo "$OOJSUI_VERSION"
fi

# Clean up temporary area
rm -rf "$NPM_DIR"
