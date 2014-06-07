#!/usr/bin/env bash
if command -v npm > /dev/null ; then
  npm install
else
  echo "You need to install Node.JS!"
  echo "See http://nodejs.org/"
  exit 1
fi
