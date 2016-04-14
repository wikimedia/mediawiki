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

if [ -n "${1:-}" ]
then
	OOJSUI_VERSION=$("$REPO_DIR/maintenance/resources/copy-in-oojs-ui.sh" --published "oojs-ui@$1")
else
	OOJSUI_VERSION=$("$REPO_DIR/maintenance/resources/copy-in-oojs-ui.sh" --published oojs-ui)
fi

# Generate commit
git checkout -B upstream-oojs-ui origin/master
cd $REPO_DIR

COMMITMSG=$(cat <<END
Update OOjs UI to v$OOJSUI_VERSION

Release notes:
 https://git.wikimedia.org/blob/oojs%2Fui.git/v$OOJSUI_VERSION/History.md
END
)

# Update composer.json as well
composer require oojs/oojs-ui $OOJSUI_VERSION --no-update

# Stage deletion, modification and creation of files. Then commit.
git add --update $TARGET_DIR
git add $TARGET_DIR
git add composer.json
git commit -m "$COMMITMSG"
