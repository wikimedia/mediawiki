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

    cd mediawiki
    npm install

## Usage

    npm run selenium

To run only one file, run each command in separate tab/window:

    chromedriver --url-base=/wd/hub --port=4444
    ./node_modules/.bin/wdio wdio.conf.vagrant.js --spec ./tests/selenium/FILE-NAME.js

## Links

- [Selenium/Node.js](https://www.mediawiki.org/wiki/Selenium/Node.js)
