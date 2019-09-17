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

 # This condition can be removed after the merge with branch REL1_31_dev, REL1_31 or REL1_27
elif [ "$BRANCH" == "REL1_31_dev-webservices-autodownload" ];
then
        mkdir extensions/BlueSpiceUEModulePDF/webservices
        rm -f extensions/BlueSpiceUEModulePDF/webservices/BShtml2PDF.war
        /usr/bin/env wget -P extensions/BlueSpiceUEModulePDF/webservices https://buildservice.bluespice.com/webservices/REL1_31/BShtml2PDF.war
 # This condition can be removed after the merge with branch REL1_31_dev, REL1_31 or REL1_27

else
    echo "Suitable web services are not found for this version: $BRANCH"
fi