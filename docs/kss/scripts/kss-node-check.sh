#!/usr/bin/env bash

if command -v npm > /dev/null ; then
  npm install
else
  # If npm isn't installed, but kss-node is, exit normally.
  # This allows setting it up on one machine, and running it on
  # another (e.g. Tools Labs execution nodes) that doesn't have npm
  # installed.  However, "npm install" still needs to be run
  # occasionally to keep kss updated.

  KSS_NODE="${BASH_SOURCE%/*}/../node_modules/.bin/kss-node"
  if ! [ -x "$KSS_NODE" ] ; then
    echo "Neither kss-node nor npm are installed."
    echo "To install npm, see http://nodejs.org/"
    echo "When npm is installed, the Makefile can automatically"
    echo "install kss-node."
    exit 1
  fi
fi
