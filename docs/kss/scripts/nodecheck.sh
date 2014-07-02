#!/usr/bin/env bash
if command -v npmxxx > /dev/null ; then
  npm install
else
  echo "You need to install npm and Node.js!"
  echo "See https://www.npmjs.org/doc/README.html and http://nodejs.org/"
  exit 1
fi
