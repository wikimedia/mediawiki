#!/bin/bash

BRANCH=$(git branch | cut -f 2 -d " ")
BRANCH=${BRANCH%%[[:space:]]}
BRANCH=${BRANCH##[[:space:]]}

if [ "$BRANCH" == "REL1_31_dev" ] || [ "$BRANCH" == "REL1_31" ];
then
        mkdir extensions/BlueSpiceUEModulePDF/webservices
        rm -f extensions/BlueSpiceUEModulePDF/webservices/BShtml2PDF.war
        /usr/bin/env wget -P extensions/BlueSpiceUEModulePDF/webservices https://buildservice.bluespice.com/webservices/REL1_31/BShtml2PDF.war

elif [ "$BRANCH" == "REL1_27" ];
then
        mkdir extensions/BlueSpiceUEModulePDF/webservices
        rm -f extensions/BlueSpiceUEModulePDF/webservices/BShtml2PDF.war
        /usr/bin/env wget -P extensions/BlueSpiceUEModulePDF/webservices https://buildservice.bluespice.com/webservices/REL1_27/BShtml2PDF.war

elif [ "$BRANCH" == "REL1_35" ] || [ "$BRANCH" == "master" ];
then
        mkdir extensions/BlueSpiceUEModulePDF/webservices
        rm -f extensions/BlueSpiceUEModulePDF/webservices/BShtml2PDF.war
        /usr/bin/env wget -P extensions/BlueSpiceUEModulePDF/webservices https://buildservice.bluespice.com/webservices/REL1_35/BShtml2PDF.war
else
    echo "Suitable web services are not found for this version: $BRANCH"
fi
