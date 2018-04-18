#!/bin/bash -eu

# This script generates a commit that updates our copy of OOUI

if [ -n "${2:-}" ]
then
	# Too many parameters
	echo >&2 "Usage: $0 [<version>]"
	exit 1
fi

REPO_DIR=$(cd "$(dirname $0)/../.."; pwd) # Root dir of the git repo working tree
TARGET_DIR="resources/lib/oojs-ui" # Destination relative to the root of the repo
NPM_DIR=$(mktemp -d 2>/dev/null || mktemp -d -t 'update-ooui') # e.g. /tmp/update-ooui.rI0I5Vir

# Prepare working tree
cd "$REPO_DIR"
git reset composer.json
git checkout composer.json
git reset -- $TARGET_DIR
git checkout -- $TARGET_DIR
git fetch origin
git checkout -B upstream-ooui origin/master

# Fetch upstream version
cd $NPM_DIR
if [ -n "${1:-}" ]
then
	npm install "oojs-ui@$1"
else
	npm install oojs-ui
fi

OOUI_VERSION=$(node -e 'console.log(require("./node_modules/oojs-ui/package.json").version);')
if [ "$OOUI_VERSION" == "" ]
then
	echo 'Could not find OOUI version'
	exit 1
fi

# Copy files, picking the necessary ones from source and distribution
rm -r "$REPO_DIR/$TARGET_DIR"

# Core and thematic code and styling
mkdir -p "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-core.js{,.map} "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-core-{wikimediaui,apex}.css "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-widgets.js{,.map} "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-widgets-{wikimediaui,apex}.css "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-toolbars.js{,.map} "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-toolbars-{wikimediaui,apex}.css "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-windows.js{,.map} "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-windows-{wikimediaui,apex}.css "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/oojs-ui-{wikimediaui,apex}.js{,.map} "$REPO_DIR/$TARGET_DIR"

# i18n
mkdir -p "$REPO_DIR/$TARGET_DIR/i18n"
cp -R ./node_modules/oojs-ui/dist/i18n "$REPO_DIR/$TARGET_DIR"

# Core images (currently two .cur files)
mkdir -p "$REPO_DIR/$TARGET_DIR/images"
cp -R ./node_modules/oojs-ui/dist/images "$REPO_DIR/$TARGET_DIR"

# WikimediaUI theme icons, indicators, and textures
mkdir -p "$REPO_DIR/$TARGET_DIR/themes/wikimediaui/images/icons"
cp ./node_modules/oojs-ui/dist/themes/wikimediaui/images/icons/*.{svg,png} "$REPO_DIR/$TARGET_DIR/themes/wikimediaui/images/icons"
mkdir -p "$REPO_DIR/$TARGET_DIR/themes/wikimediaui/images/indicators"
cp ./node_modules/oojs-ui/dist/themes/wikimediaui/images/indicators/*.{svg,png} "$REPO_DIR/$TARGET_DIR/themes/wikimediaui/images/indicators"
mkdir -p "$REPO_DIR/$TARGET_DIR/themes/wikimediaui/images/textures"
cp ./node_modules/oojs-ui/dist/themes/wikimediaui/images/textures/*.{gif,svg} "$REPO_DIR/$TARGET_DIR/themes/wikimediaui/images/textures"

cp ./node_modules/oojs-ui/src/themes/wikimediaui/*.json "$REPO_DIR/$TARGET_DIR/themes/wikimediaui"

# Apex theme icons, indicators, and textures
mkdir -p "$REPO_DIR/$TARGET_DIR/themes/apex"
cp ./node_modules/oojs-ui/src/themes/apex/*.json "$REPO_DIR/$TARGET_DIR/themes/apex"

# WikimediaUI LESS variables for sharing
cp ./node_modules/oojs-ui/dist/wikimedia-ui-base.less "$REPO_DIR/$TARGET_DIR"

# Misc stuff
cp ./node_modules/oojs-ui/dist/AUTHORS.txt "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/History.md "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/LICENSE-MIT "$REPO_DIR/$TARGET_DIR"
cp ./node_modules/oojs-ui/dist/README.md "$REPO_DIR/$TARGET_DIR"

# Clean up temporary area
rm -rf "$NPM_DIR"

# Generate commit
cd $REPO_DIR

COMMITMSG=$(cat <<END
Update OOUI to v$OOUI_VERSION

Release notes:
 https://phabricator.wikimedia.org/diffusion/GOJU/browse/master/History.md;v$OOUI_VERSION
END
)

# Update composer.json as well
composer require oojs/oojs-ui $OOUI_VERSION --no-update

# Stage deletion, modification and creation of files. Then commit.
git add --update $TARGET_DIR
git add $TARGET_DIR
git add composer.json
git commit -m "$COMMITMSG"
