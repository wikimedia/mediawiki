#!/usr/bin/env bash
# Run phpunit tests
set -ex

PHP=php

if [[ $(phpenv version-name) =~ hhvm ]]; then
    PHP="hhvm -c $(pwd)/.travis/hhvm.ini --php"
fi

exec $PHP tests/phpunit/phpunit.php
