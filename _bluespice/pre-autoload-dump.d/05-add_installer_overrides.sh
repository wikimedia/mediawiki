#!/bin/bash
# Helper script to load the installer into the proper directory
# Copyright: 2021
# License: GPLv3

GREEN='\033[0;32m'
RED='\033[0;31m'
PURPLE='\033[0;35m'
NC='\033[0m'

BRANCH=$(git branch | cut -f 2 -d " ")
BRANCH=${BRANCH%%[[:space:]]}
BRANCH=${BRANCH##[[:space:]]}
BRANCH="${BRANCH//[$'\t\r\n ']}"

if [ "$BRANCH" == "4.1.x" ]
then
	BRANCH="REL1_35"
fi

printf "\n${PURPLE}Fetching installer: ${NC}"

if ! [ -d "mw-config/overrides/.git" ]
then
	rm -rf mw-config/overrides
	git clone -b $BRANCH --depth 1 https://gerrit.wikimedia.org/r/bluespice/mw-config/overrides mw-config/overrides
else
	git -C mw-config/overrides/ pull
fi

printf "${GREEN}[ DONE ]${NC}\n"