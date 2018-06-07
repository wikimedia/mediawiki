#!/usr/bin/env bash
set -euo pipefail
# Check the command before running in background so
# that it can actually fail and have a descriptive error
hash chromedriver
chromedriver --url-base=/wd/hub --port=4444 &
CHROME_DRIVER_PID=$!
echo chrome driver running with PID $CHROME_DRIVER_PID
# Make sure it is killed to prevent file descriptors leak
function kill_chromedriver() {
    kill $CHROME_DRIVER_PID > /dev/null
}
trap kill_chromedriver EXIT
npm run selenium-test
