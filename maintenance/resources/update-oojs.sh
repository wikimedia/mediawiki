#!/bin/bash -eu

# This script generates a commit that updates our copy of OOjs

if [ -n "${2:-}" ]
then
	# Too many parameters
	echo >&2 "Usage: $0 [<version>]"
	exit 1
fi

REPO_DIR=$(cd "$(dirname $0)/../.."; pwd) # Root dir of the git repo working tree
TARGET_DIR="resources/lib/oojs" # Destination relative to the root of the repo

if [ -n "${1:-}" ]
then
       OOJS_VERSION=$("$REPO_DIR/maintenance/resources/copy-in-oojs.sh" --published "oojs@$1")
else
       OOJS_VERSION=$("$REPO_DIR/maintenance/resources/copy-in-oojs.sh" --published oojs)
fi

# Generate commit
cd $REPO_DIR

COMMITMSG=$(cat <<END
Update OOjs to v$OOJS_VERSION

Release notes:
 https://git.wikimedia.org/blob/oojs%2Fcore.git/v$OOJS_VERSION/History.md
END
)

# Stage deletion, modification and creation of files. Then commit.
git checkout -B upstream-oojs origin/master
git add --update $TARGET_DIR
git add $TARGET_DIR
git commit -m "$COMMITMSG"
