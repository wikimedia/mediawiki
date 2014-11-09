#!/usr/bin/env bash
# Run phpunit tests
set -ex

PHP=php

if [[ $(phpenv version-name) =~ hhvm ]]; then
    # Bug 73177: Configure HHVM to run object destructors
    PHP="hhvm -v Eval.EnableObjDestructCall=1 -v Eval.EnableZendCompat=1 --php"
fi

exec $PHP tests/phpunit/phpunit.php
