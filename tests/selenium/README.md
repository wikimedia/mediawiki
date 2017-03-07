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
    ./node_modules/.bin/wdio tests/selenium/wdio.conf.vagrant.js --spec ./tests/selenium/FILE-NAME.js

## Outside of Vagrant

You are expected to set the following environment variables:

MW_SERVER: set to the value of your $wgServer
MW_SCRIPT_PATH: ditto with  $wgScriptPath
MEDIAWIKI_USER: username of an account that can create users on the wiki.
MEDIAWIKI_PASSWORD: password for above user

Then either:

  npm run selenium

Or:

  cd tests/selenium
  ../../node_modules/.bin/wdio --spec page.js

## Links

- [Selenium/Node.js](https://www.mediawiki.org/wiki/Selenium/Node.js)
