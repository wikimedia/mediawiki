#!/bin/bash -eu

# This script generates a commit that updates our copy of OOjs UI

if [ -n "${2:-}" ]
then
	# Too many parameters
	echo >&2 "Usage: $0 [<version>]"
	exit 1
fi

REPO_DIR=$(cd "$(dirname $0)/../.."; pwd) # Root dir of the git repo working tree
TARGET_DIR="resources/lib/oojs-ui" # Destination relative to the root of the repo
NPM_DIR=$(mktemp -d 2>/dev/null || mktemp -d -t 'update-oojs-ui') # e.g. /tmp/update-oojs-ui.rI0I5Vir

# Prepare working tree
cd "$REPO_DIR"
git reset composer.json
git checkout composer.json
git reset -- $TARGET_DIR
git checkout -- $TARGET_DIR
git fetch origin
git checkout -B upstream-oojs-ui origin/master

# Fetch upstream version
cd $NPM_DIR
if [ -n "${1:-}" ]
then
	npm install "oojs-ui@$1"
else
	npm install oojs-ui
fi

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

# Clean up temporary area
rm -rf "$NPM_DIR"

# Generate commit
cd $REPO_DIR

COMMITMSG=$(cat <<END
Update OOjs UI to v$OOJSUI_VERSION

Release notes:
 https://phabricator.wikimedia.org/diffusion/GOJU/browse/master/History.md;v$OOJSUI_VERSION
END
)

# Update composer.json as well
composer require oojs/oojs-ui $OOJSUI_VERSION --no-update

# Stage deletion, modification and creation of files. Then commit.
git add --update $TARGET_DIR
git add $TARGET_DIR
git add composer.json
git commit -m "$COMMITMSG"
