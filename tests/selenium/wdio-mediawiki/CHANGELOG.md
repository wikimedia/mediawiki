# Changelog

## 6.0.0 / 2025-10-20

With wdio-mediawiki 6.0 we replaced mwbot with internal code. This is a breaking change if you use any API functionality in your test and use the API from wdio-mediawiki. If you miss API functionality for your test, please create a task in Phabricator with the Test Platform tag.

The change was done in two steps:

1. mwbot was exposed in the API, meaning you as a user could get hold of the mwbot instance by using something like:
```
import { mwbot } from 'wdio-mediawiki/Api.js';
...
const bot = await mwbot();
```
We changed that to not expose our implementation so it's easier in the future to change API backend. This change removed the exposed mwbot() and made Api.js expose the API functions directly. The change was done in T404596.

2. The next step removed the actual mwbot dependency and implements our own functionality to talk to the API (using built in NodeJS fetch). The change was done in T404361. There's an example in https://gerrit.wikimedia.org/r/c/mediawiki/extensions/examples/+/1197258 e what you need to do to upgrade to 6.0 if you used mwbot.

If you don't pass on any user/password when setting up the API client the `browser.options.capabilities[ 'mw:user' ]` and `browser.options.capabilities[ 'mw:pwd' ]` will be used. For specific functions that need a username, you always need to pass on the username from this version.

There where also a couple of minor changes to make the testing more stable in CI:

* Disable infobars (T403827)
* Disable settings in Chrome to make it more stable (T403357)
* Log the URL that we use for the tests (T403004)
* Wait for mw.loader.using in waitForModuleState() (T397014)

## 5.1.0 / 2025-07-18

* Fix how to count number of tests for Prometheus. (T399677)
* Get spec test retries in Prometheus per project. (T398782)
* Tag project/test metrics per beta or ci. (T399685)
* Throw exception when API response lacks expected field. (T393428)

## 5.0.1 / 2025-07-04

* Use ECMAScript modules in RunJobs.js. (T398046)

## 5.0.0 / 2025-06-26

* Use ECMAScript modules. (T373125)

## 4.1.3 / 2025-06-24

* Update waitForModuleState to use mw.loader.using for wdio 9 (T397014)

## 4.1.2 / 2025-06-17

* Fix skipping tests for PrometheusReporter (T397030)

## 4.1.1 / 2025-06-13

* Use package name as project name for Prometheus (T396710)

## 4.1.0 / 2025-06-12

* Add Prometheus support for CI usage. (T391078)

## 4.0.0 / 2025-06-04

* Upgrade WebdriverIO to v9. (T372633)

## 3.0.1 / 2025-05-28

* Fix specs in configuration file. (T395322)

## 3.0.0 / 2025-05-26

* Pin browser version in CI. (T391320)
* Upgrade WebdriverIO to v8. (T324766)

## 2.7.1 / 2025-03-27

* Set `--disable-gpu` Chromium arg when running in Docker. (T389536)
* Exit the process early from global `uncaughtException`. (T389562)
* Skip video recording if ffmpeg is unavailable. (T381727)

## 2.7.0 / 2025-01-22

* Api: Add `api.addUserToGroup()` to add user to a user group.

## 2.6.0 / 2025-01-09

* Skip wikitext-specific tests if NS_MAIN isn't wikitext. (T358530)
* Add LoginPage.getActualUsername().
* Wait for form submission in `LoginPage.login()`.
* Wait for the page to be fully loaded in `Page.openTitle()`. (T381739)
* Add random element to junit-reporter file name.

## 2.5.0 / 2024-01-24

* Screenshots no longer work with WebdriverIO v8, just with v7. (T347137)
* Fix tests on macOS + Node.js v18. (T355556)

## 2.4.0 / 2023-11-16

* Screenshots work with both WebdriverIO v7 and v8

## 2.3.0 / 2023-09-21

* Refactor waitForModuleState and saveScreenshot to async/await. (T337463)
* Upgrade mwbot from 2.0.0 to 2.1.3.
* Delete automationProtocol setting.
* Revert "Skip wikitext-specific tests if NS isn't wikitext". (T303737)
* Revert "Default to larger window size". (T317879)

## 2.2.0 / 2022-07-29

* Default to larger window size (T314115)

## 2.1.0 / 2022-05-19

* Use @wdio/spec-reporter.

## 2.0.0 / 2022-01-11

The wdio-mediawiki library now requires WebdriverIO async mode.

* Util: Added `isTargetNotWikitext()`.
* CreateAccountPage: Added initial version.

## 1.2.0 / 2021-01-11

* Set default configuration to retry tests in a spec file once upon failure

## 1.1.1 / 2021-05-26

* Fix `Cannot find module 'dotenv'`.

## 1.1.0 / 2021-05-21

* Api: Update mwbot to version 2.0, as returned by `api.bot()`.
* Added new `wdio-defaults.conf.js` entrypoint.
* index: Changed `saveScreenshot()` filenames to use an ISO-formatted timestamp.

## 1.0.0 / 2019-11-05

The wdio-mediawiki library now requires webdriverio 5 and Node 10 (or later).

* The global `username` and `password` config keys have been renamed to
  `mwUser` and `mwPwd`. These are used as the defaults for `Api` and `LoginPage`
  methods that require user credentials.
* Api: The `api.edit()` and `api.delete()` methods were removed as they encouraged
  an anti-pattern where each user action was preceded by its own API login sequence.
  Use the `bot.edit()` and `bot.delete()` methods directly instead, and re-use the
  `bot` object where possible. See [README](./README.md) for a usage example.

## 0.5.0 / 2019-09-18

* Api: Added `bot()` method.

## 0.4.0 / 2019-07-18

* Util: Added a `waitForModuleState()` method.
* Api: Added optional `username`, `password` and `baseUrl` parameters to `edit()` method.
* RunJobs: Unpublished `getJobCount()`, `log()`, `runThroughMainPageRequests()` methods.

## 0.3.0 / 2019-01-25

* RunJobs: Added initial version.
* Page: Added a `fragment` parameter to `openTitle()`.
* Api: Added `blockUser()` and `unblockUser()` methods.
* Util: Changed `getTestString()` to include U+2603 (Snowman).
* (All): Fixed internal `require()` calls to use relative paths.

## 0.2.0 / 2018-06-25

* Util: Added getTestString().

## 0.1.0 / 2018-05-02

* Api: Added initial version.
* Page: Added initial version.
* BlankPage: Added initial version.
* LoginPage: Added initial version.
