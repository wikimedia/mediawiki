# Selenium tests

## Prerequisites

- [Chrome](https://www.google.com/chrome/)
- [ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/)
- [Node.js](https://nodejs.org/en/)
- [MediaWiki-Vagrant](https://www.mediawiki.org/wiki/MediaWiki-Vagrant)

Set up MediaWiki-Vagrant:

    cd mediawiki/vagrant
    vagrant up

## Installation

    npm install

## Usage

Until 323401 is merged into mediawiki/core, get it manually:

    cd mediawiki
    git review -d 323401

    npm run selenium

To run only one file:

    NODE_CONFIG_DIR='./tests/selenium/config' ./node_modules/.bin/mocha --timeout 20000 tests/selenium/file_name.js

To run only one test:

    NODE_CONFIG_DIR='./tests/selenium/config' ./node_modules/.bin/mocha --timeout 20000 tests/selenium -g 'part of test name'

## Links

- [Selenium/Node.js](https://www.mediawiki.org/wiki/Selenium/Node.js)
