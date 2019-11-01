#!/bin/bash

set -eo pipefail

mkdir -p /tmp/php
mkdir -p extensions

git clone --depth 1 https://gerrit.wikimedia.org/r/mediawiki/extensions/VisualEditor.git /var/www/html/extensions/VisualEditor
git clone --depth 1 https://gerrit.wikimedia.org/r/mediawiki/skins/Vector /var/www/html/skins/Vector
cd /var/www/html/extensions/VisualEditor
git submodule update --depth 10 --init

cd /var/www/html
composer install
cat <<PHP > LocalSettings.php
<?php
require_once '/var/config/LocalSettings.php';
PHP
