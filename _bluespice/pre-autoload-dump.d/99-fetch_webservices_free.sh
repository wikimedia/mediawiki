#!/bin/bash

GREEN='\033[0;32m'
RED='\033[0;31m'
PURPLE='\033[0;35m'
NC='\033[0m'

BRANCH=$(git branch | cut -f 2 -d " ")
BRANCH=${BRANCH%%[[:space:]]}
BRANCH=${BRANCH##[[:space:]]}
BRANCH="${BRANCH//[$'\t\r\n ']}"

mkdir -p extensions/BlueSpiceUEModulePDF/webservices > /dev/null 2>&1
rm -f extensions/BlueSpiceUEModulePDF/webservices/BShtml2PDF.war > /dev/null 2>&1
printf "\n${PURPLE}Downloading: ${NC}BShtml2PDF.war${NC} "
/usr/bin/env wget -P extensions/BlueSpiceUEModulePDF/webservices https://buildservice.bluespice.com/webservices/$BRANCH/BShtml2PDF.war > /dev/null 2>&1
if [ $? -gt 0 ]; then
    printf "${RED}[ FAILED ]${NC}\n"
else
    printf "${GREEN}[ DONE ]${NC}\n"
fi
