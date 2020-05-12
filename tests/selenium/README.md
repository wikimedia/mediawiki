# Selenium tests

## Getting started

See <https://www.mediawiki.org/wiki/Selenium/Node.js> for how to best
run these locally. Below the internal prerequisites are documented,
but you might not need to install these yourself.

## Prerequisites

- [Chromium](https://www.chromium.org/) or [Chrome](https://www.google.com/chrome/)
- [ChromeDriver](https://chromedriver.chromium.org/downloads)
- [Node.js](https://nodejs.org/en/)

## Usage

There are three supported modes of running the tests.

#### Headless

The Selenium tests default to headless mode, unless a `DISPLAY` environment variable is set.
This variable may be set on Linux desktop and XQuartz environments. To run headless there,
unset the `DISPLAY` environment variable first.

    npm run selenium

Or:

    DISPLAY= npm run selenium

#### Visible browser

To see the browser window, ensure the `DISPLAY` variable is set. On Linux desktop and in XQuartz
environments this is probably set already. On macOS, set it to a dummy value like `1`.

    DISPLAY=1 npm run selenium

#### Video recording

Starting from WebdriverIO v5, [wdio-video-reporter](https://www.npmjs.com/package/wdio-video-reporter) NPM package is used to record videos of test runs. By default, videos for all tests are recorded and saved under `tests/selenium/log` directory.

#### Filter

Run a specific spec:

    npm run selenium -- --spec tests/selenium/specs/page.js

To filter by test case, e.g. with the name containing "preferences":

    npm run selenium -- --mochaOpts.grep preferences

#### Configuration

The following environment variables decide where to find MediaWiki and how to login:

- `MW_SERVER`: The value of `$wgServer`.
- `MW_SCRIPT_PATH`: The value of `$wgScriptPath`.
- `MEDIAWIKI_USER`: Username of a wiki account with sysop rights.
- `MEDIAWIKI_PASSWORD`: Password for this user.

## Further reading

- [Selenium/Node.js](https://www.mediawiki.org/wiki/Selenium/Node.js) on mediawiki.org
