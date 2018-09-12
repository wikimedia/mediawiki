#!/bin/sh
# Helper script to load git submodules for some extensions
# call example: ./load-git-submodules
# gerrit username will be asked and save if unknown
# Author: Leonid Verhovskij <l.verhovskij@gmail.com>
# Copyright: 2017
# License: GPLv3

mv vendor vendor_by_composer
git submodule update --init --recursive
rm -rf vendor
mv vendor_by_composer vendor
