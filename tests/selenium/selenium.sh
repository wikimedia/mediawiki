#!/usr/bin/env bash
set -euo pipefail
chromedriver --url-base=/wd/hub --port=4444 &
# Make sure it is killed to prevent file descriptors leak
function kill_chromedriver() {
    killall chromedriver > /dev/null
}
trap kill_chromedriver EXIT
npm run selenium-test
