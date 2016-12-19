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

To run only one file (for example page.js), you first need to spawn the chromedriver:

    chromedriver --url-base=/wd/hub --port=4444

Then in another terminal:

    cd mediawiki/tests/selenium
    ../../node_modules/.bin/wdio --spec page.js

The runner reads the config file `wdio.conf.js` and runs the spec listed in
`page.js`.

The defaults in the configuration files aim are targetting  a MediaWiki-Vagrant
installation on installation on http://127.0.0.1:8080 with a user Admin and
password 'vagrant'.  Those settings can be overriden using environment
variables:

`MW_SERVER`: to be set to the value of your $wgServer
`MW_SCRIPT_PATH`: ditto with  $wgScriptPath
`MEDIAWIKI_USER`: username of an account that can create users on the wiki.
`MEDIAWIKI_PASSWORD`: password for above user

Example:

    MW_SERVER=http://example.org MW_SCRIPT_PATH=/dev/w npm run selenium

## Links

- [Selenium/Node.js](https://www.mediawiki.org/wiki/Selenium/Node.js)
