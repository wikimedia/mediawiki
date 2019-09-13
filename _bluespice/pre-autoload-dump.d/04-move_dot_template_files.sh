#!/bin/sh
find . -maxdepth 3 -name "*.php.template" -exec bash -c 'cp $1 ${1%.template}' _ {} \;
