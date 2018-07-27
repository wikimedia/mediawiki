#!/bin/sh
# Helper script to load git submodules for some extensions
# call example: ./load-git-submodules
# gerrit username will be asked and save if unknown
# Author: Leonid Verhovskij <l.verhovskij@gmail.com>
# Copyright: 2017
# License: GPLv3

EXT_PATH_VISUALEDITOR="extensions/VisualEditor"

if [ -d $EXT_PATH_VISUALEDITOR ] ; then
	cd $EXT_PATH_VISUALEDITOR && git submodule foreach --recursive git reset --hard && git submodule update --init --recursive
fi

