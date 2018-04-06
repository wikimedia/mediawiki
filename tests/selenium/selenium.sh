#!/bin/bash
chromedriver --url-base=/wd/hub --port=4444 &
# Make sure it is killed to prevent file descriptors leak
function kill_chromedriver() {
    killall chromedriver > /dev/null
}
trap kill_chromedriver EXIT

./node_modules/.bin/wdio tests/selenium/wdio.conf.js
./node_modules/.bin/wdio tests/selenium/wdio.conf.js --framework cucumber
