#!/bin/bash -eu

# This script generates a commit that updates the extensions/ submodules
# ./bin/updateSubmodule.sh repo        updates the named submodule to the current branch
# ./bin/updateSubmodule.sh repo hash   updates to specified hash

# cd to the MediaWiki root directory
cd $(cd $(dirname $0)/..; pwd)

# Ensure input is correct
if [ -z "${1:-}" ]
then
	echo >&2 "Usage: updateSubmodule.sh <repo> [<hash>]"
	exit 1
fi


# Check that both working directories are clean
if git status -uno --ignore-submodules | grep -i changes > /dev/null
then
	echo >&2 "Working directory must be clean"
	exit 1
fi

cd extensions/$1
if git status -uno --ignore-submodules | grep -i changes > /dev/null
then
	echo >&2 "Submodule working directory must be clean"
	exit 1
fi
cd ../..


# Figure out what to set the submodule to
if [ -n "${2:-}" ]
then
	TARGET="$2"
	TARGETDESC="$2"
else
	TARGET=`git branch --contains | grep '^*' | cut -d ' ' -f 2-`
	TARGETDESC="HEAD ($(git rev-parse --short origin/master))"
fi

git fetch origin
# Create sync-repos branch if needed and reset it to master
git checkout -B sync-repos origin/$TARGET
git submodule update

cd extensions/$1
git fetch origin

# Generate commit summary
# TODO recurse
NEWCHANGES=$(git log ..$TARGET --oneline --no-merges --reverse --color=never)
NEWCHANGESDISPLAY=$(git log ..$TARGET --oneline --no-merges --reverse --color=always)
COMMITMSG=$(cat <<END
Update $1 core submodule to $TARGETDESC

New changes:
$NEWCHANGES
END
)

# Check out the right branch of MW core
git checkout $TARGET

# Commit
cd ../..
git commit extensions/$1 -m "$COMMITMSG" > /dev/null
if [ "$?" == "1" ]
then
	echo >&2 "No changes"
else
	cat >&2 <<END


Created commit with changes:
$NEWCHANGESDISPLAY
END
fi
