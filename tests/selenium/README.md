# Selenium tests

## Prerequisites

- [Chrome](https://www.google.com/chrome/)
- [ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/)
- [Node.js](https://nodejs.org/en/)

If using MediaWiki-Vagrant:

    cd mediawiki/vagrant
    vagrant up

## Installation

    cd mediawiki
    npm install

## Usage

    npm run selenium

By default, Chrome will run in headless mode. If you want to see Chrome, set DISPLAY
environment variable to any value:

    DISPLAY=1 npm run selenium

To run only one test (for example specs/page.js), you first need to start Chromedriver:

    chromedriver --url-base=wd/hub --port=4444

Then, in another terminal:

    npm run selenium-test -- --spec tests/selenium/specs/page.js

You can also filter specific cases, for ones that contain the string 'preferences':

    npm run selenium-test -- tests/selenium/specs/user.js --mochaOpts.grep preferences

The runner reads the configuration from `wdio.conf.js`. The defaults target
a MediaWiki-Vagrant installation on `http://127.0.0.1:8080` with a user "Admin"
and password "vagrant".  Those settings can be overridden using environment
variables:

- `MW_SERVER`: to be set to the value of your $wgServer
- `MW_SCRIPT_PATH`: ditto with $wgScriptPath
- `MEDIAWIKI_USER`: username of an account that can create users on the wiki
- `MEDIAWIKI_PASSWORD`: password for above user

Example:

    MW_SERVER=http://example.org MW_SCRIPT_PATH=/dev/w npm run selenium

## Further reading

- [Selenium/Node.js](https://www.mediawiki.org/wiki/Selenium/Node.js)
