#!/bin/bash
# Helper script to load the installer into the proper directory
# Copyright: 2021
# License: GPLv3

BRANCH=$(git branch | cut -f 2 -d " ")
BRANCH=${BRANCH%%[[:space:]]}
BRANCH=${BRANCH##[[:space:]]}
BRANCH="${BRANCH//[$'\t\r\n ']}"

if [ "$BRANCH" == "4.1.x" ]
then
	BRANCH="REL1_35"
fi

rm -rf mw-config/overrides
git clone -b $BRANCH --depth 1 https://gerrit.wikimedia.org/r/bluespice/mw-config/overrides mw-config/overrides

