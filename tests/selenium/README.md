# Selenium tests

## Getting started

See <https://www.mediawiki.org/wiki/Selenium> for how to best
run these locally. Below the internal prerequisites are documented,
but you might not need to install these yourself.

## Prerequisites

- [Chromium](https://www.chromium.org/Home) or [Chrome](https://www.google.com/chrome)
- [Node.js](https://nodejs.org)

## Usage

There are three supported modes of running the tests.

#### Headless

The Selenium tests default to headless mode, unless a `DISPLAY` environment variable is set.
This variable may be set on Linux desktop and XQuartz environments. To run headless there,
unset the `DISPLAY` environment variable first.

    npm run selenium-test

Or:

    DISPLAY= npm run selenium-test

### Visible browser

To see the browser window, ensure the `DISPLAY` variable is set. On Linux desktop and in XQuartz
environments this is probably set already. On macOS, set it to a dummy value like `1`.

    DISPLAY=1 npm run selenium-test

### Video recording

To capture a video, the tests have to run in the context of an X11 server, with the `DISPLAY`
environment variable set to its display name. If the shell has no X11 server or if you want
to hide the output, you can also launch a virtual X11 display using Xvfb. Recording videos
is currently supported only on Linux, and is triggered by the `DISPLAY` value starting with
a colon (as Xvfb typically would).

Example test run in [Fresh](https://gerrit.wikimedia.org/g/fresh).

    fresh-node -env -net
    export DISPLAY=:1
    Xvfb "$DISPLAY" -screen 0 1280x1024x24 &
    npm run selenium-test

## Filter

Run a specific spec:

    npm run selenium-test -- --spec tests/selenium/specs/page.js

To filter by test case, e.g. with the name containing "preferences":

    npm run selenium-test -- --mochaOpts.grep preferences

## Configuration

The following environment variables decide where to find MediaWiki and how to login:

- `MW_SERVER`: The value of `$wgServer`.
- `MW_SCRIPT_PATH`: The value of `$wgScriptPath`.
- `MEDIAWIKI_USER`: Username of a wiki account with sysop rights.
- `MEDIAWIKI_PASSWORD`: Password for this user.

## Further reading

- [Selenium](https://www.mediawiki.org/wiki/Selenium) on mediawiki.org
