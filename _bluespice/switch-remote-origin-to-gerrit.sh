#!/bin/bash
# Helper script to switch git origin from github.com/wikimedia/... to wikmedia gerrit equivalent, shuld be called inside of related extension directory
# call example: (extension/MyExtension)$ ../../switch-remote-origin-to-gerrit
# gerrit username will be asked and save if unknown
# Author: Leonid Verhovskij <l.verhovskij@gmail.com>
# Copyright: 2017
# License: GPLv3
GERRIT_USER_FILE=~/.gerrit_user
if [ ! -f $GERRIT_USER_FILE ] ; then
	echo "File with username does not exists, what is your gerrit username?"
	read GERRIT_USERNAME
	echo $GERRIT_USERNAME > $GERRIT_USER_FILE
	echo "Username have been saved in $GERRIT_USER_FILE"
fi
GERRIT_USER=`cat $GERRIT_USER_FILE`
REMOTE_URL=`git remote get-url origin`
GITHUB_MATCH="https://github.com/wikimedia/"
GERRIT_URL="ssh://$GERRIT_USER@gerrit.wikimedia.org:29418"
git remote get-url origin | grep -q $GITHUB_MATCH

if [ $? -eq 0 ] ; then
	echo "replace origin url..."
	WITHOUT_PREFIX=${REMOTE_URL#$GITHUB_MATCH}
	WITHOUT_SUFFIX=${WITHOUT_PREFIX%.git}
	IFS='-' read -ra ADDR <<< $WITHOUT_SUFFIX
	NEW_URL=$GERRIT_URL
	for i in "${ADDR[@]}"; do
	    # process "$i"
		NEW_URL=$NEW_URL/$i
	done
	git remote set-url origin $NEW_URL
	git remote set-url --push origin $NEW_URL
	echo "done";
else
	echo "url does not contain github.com/wikimedia"
fi
