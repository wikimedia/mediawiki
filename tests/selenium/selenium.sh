#!/usr/bin/env bash
set -euo pipefail
# Check the command before running in background so
# that it can actually fail and have a descriptive error
hash chromedriver
chromedriver --url-base=/wd/hub --port=4444 &
# Make sure it is killed to prevent file descriptors leak
function kill_chromedriver() {
    killall chromedriver > /dev/null
}
trap kill_chromedriver EXIT
npm run selenium-test
