#!/usr/bin/env bash

# This script generates a commit that updates our copy of OOjs UI

if [ -n "$2" ]
then
	# Too many parameters
	echo >&2 "Usage: $0 [<version>]"
	exit 1
fi

REPO_DIR=$(cd "$(dirname $0)/../.."; pwd) # Root dir of the git repo working tree
TARGET_DIR="resources/lib/oojs-ui" # Destination relative to the root of the repo
NPM_DIR=$(mktemp -d 2>/dev/null || mktemp -d -t 'update-oojs-ui') # e.g. /tmp/update-oojs-ui.rI0I5Vir

# Prepare working tree
cd "$REPO_DIR" &&
git reset composer.json && git checkout composer.json &&
git reset $TARGET_DIR && git checkout $TARGET_DIR && git fetch origin &&
git checkout -B upstream-oojs-ui origin/master || exit 1

# Fetch upstream version
cd $NPM_DIR
if [ -n "$1" ]
then
	npm install "oojs-ui@$1" || exit 1
else
	npm install oojs-ui || exit 1
fi

OOJSUI_VERSION=$(node -e 'console.log(require("./node_modules/oojs-ui/package.json").version);')
if [ "$OOJSUI_VERSION" == "" ]
then
	echo 'Could not find OOjs UI version'
	exit 1
fi

# Copy files, excluding:
# * the Apex theme files,
# * the minimised distribution files, and
# * the RTL sheets for non-CSSJanus environments
# * the raster- and vector-only distribution sheets
rsync --force --recursive --delete \
	--exclude '*apex*' \
	--exclude 'oojs-ui*.min.*' \
	--exclude 'oojs-ui*.rtl.css' \
	--exclude 'oojs-ui*.raster.css' \
	--exclude 'oojs-ui*.vector.css' \
	./node_modules/oojs-ui/dist/ "$REPO_DIR/$TARGET_DIR" || exit 1

# Clean up temporary area
rm -rf "$NPM_DIR"

# Generate commit
cd $REPO_DIR || exit 1

COMMITMSG=$(cat <<END
Update OOjs UI to v$OOJSUI_VERSION

Release notes:
 https://git.wikimedia.org/blob/oojs%2Fui.git/v$OOJSUI_VERSION/History.md
END
)

# Update composer.json as well
composer require oojs/oojs-ui $OOJSUI_VERSION --no-update

# Stage deletion, modification and creation of files. Then commit.
git add --update $TARGET_DIR && git add $TARGET_DIR && git add composer.json && git commit -m "$COMMITMSG" || exit 1
