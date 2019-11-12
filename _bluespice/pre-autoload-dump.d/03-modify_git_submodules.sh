#!/bin/bash

EXT_PATH_SYNTAXHIGHLIGHT="extensions/SyntaxHighlight_GeSHi"

if [ -d $EXT_PATH_SYNTAXHIGHLIGHT ] ; then
        cd $EXT_PATH_SYNTAXHIGHLIGHT && git checkout -b REL1_30 origin/REL1_30 && composer update --no-dev
fi
