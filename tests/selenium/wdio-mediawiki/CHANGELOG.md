# Changelog

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
