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

By default, Chrome will run in headless mode. If you want to see Chrome, set DISPLAY
environment variable to any value:

    DISPLAY=:1 npm run selenium

To run only one file (for example page.js), you first need to spawn the chromedriver:

    chromedriver --url-base=wd/hub --port=4444

Then in another terminal:

    cd tests/selenium
    ../../node_modules/.bin/wdio --spec specs/page.js

To run only one test (name contains string 'preferences'):

    ../../node_modules/.bin/wdio --spec specs/user.js --mochaOpts.grep preferences

The runner reads the config file `wdio.conf.js` and runs the spec listed in
`page.js`.

The defaults in the configuration files aim are targeting a MediaWiki-Vagrant
installation on http://127.0.0.1:8080 with a user Admin and
password 'vagrant'.  Those settings can be overridden using environment
variables:

`MW_SERVER`: to be set to the value of your $wgServer
`MW_SCRIPT_PATH`: ditto with $wgScriptPath
`MEDIAWIKI_USER`: username of an account that can create users on the wiki
`MEDIAWIKI_PASSWORD`: password for above user

Example:

    MW_SERVER=http://example.org MW_SCRIPT_PATH=/dev/w npm run selenium

## Links

- [Selenium/Node.js](https://www.mediawiki.org/wiki/Selenium/Node.js)
