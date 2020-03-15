# Called via "npm run selenium", see /package.json.

set -euo pipefail

# Manually check if chromedriver is installed and let it fail early with a descriptive
# error if not. Without this, it would fail silently because we run the next command
# in the background.
hash chromedriver

chromedriver --url-base=wd/hub --port=4444 &
CHROME_DRIVER_PID=$!

# Stop it automatically no matter how the script ended
# Uses 'kill' instead of 'killall' to increase chances of this working on Windows
function stop_chromedriver() {
  kill $CHROME_DRIVER_PID > /dev/null
}

trap stop_chromedriver EXIT

npm run selenium-test -- "$@"
