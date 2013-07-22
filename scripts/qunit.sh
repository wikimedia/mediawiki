#!/usr/bin/env bash
echo "Running QUnit tests..."
if command -v phantomjs > /dev/null ; then
  URL=${MEDIAWIKI_URL:-"http://127.0.0.1:80"}
  if [ -z "$1" ]; then
    FILTER=""
  else
    FILTER="&filter=$1"
  fi
  echo "Using $URL as a development environment host."
  echo "To specify a different host set MEDIAWIKI_URL environment variable"
  echo '(e.g. by running "export MEDIAWIKI_URL=http://localhost:8080/w")'
  phantomjs tests/externals/phantomjs-qunit-runner.js "$URL/index.php/Special:JavaScriptTest/qunit?$FILTER"
else
  echo "You need to install PhantomJS to run QUnit tests in terminal!"
  echo "See http://phantomjs.org/"
  exit 1
fi
