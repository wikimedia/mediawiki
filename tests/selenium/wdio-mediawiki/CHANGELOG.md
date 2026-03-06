# Changelog

## 6.3.3 / 2026-03-06
In 6.3.0 how you run tests locally on your machine was changed so it always use a browser window. That
change was unintentional and with this release we bring back the old behavoir check if DISPLAY is exported.

* Bring back DISPLAY to enable/disable headless local (T418833)

## 6.3.2 / 2026-02-20

* Make it possible for users to wait for RunJobs.run() (T415658)

## 6.3.1 / 2026-02-20

* Await the client for RunJobs (T415658)

## 6.3.0 / 2026-02-18

This new release of wdio-mediawiki has one main focus:
-  making core tests and tests that use wdio-mediawiki run faster in CI.
The overall goal is to shorten the developer feedback loop.

To achieve this, we are increasing the number of test suites that run in parallel.
For you as a developer, this means that tests in different suites must not depend on each other.
As stated in the documentation:
> "Tests don't depend on others. The test suite should pass when tests are running in random order or in parallel."
https://www.mediawiki.org/wiki/Selenium/Explanation/Anti-patterns

With this release, we also start following WebdriverIO best practices by enabling Chrome headless mode by default.
This reduces CPU usage in CI, since we Chrome in headless is faster and we will no longer record videos with FFmpeg by default.

If you have failing tests in CI and the logs are not enough to debug the issue,
you can still enable video recording by adding the following to your wdio configuration:

```
recordVideo: true,
useBrowserHeadless: false,
```

We also removed default screenshots for passing tests. Instead of taking screenshots
for both passing and failing tests, we now only create screenshots for failing tests.

If you still need screenshots for passing tests, you can re-enable them temporarly with:
```screenshotsOnFailureOnly: false```

By reducing the time spent recording videos and taking screenshots for passing tests,
we can spend more time running tests in parallel, which further improves CI speed and shortens the feedback loop.

Finally, with this release we increase the default `maxInstances` setting from 1 to 6 when you run in CI.
This means that if you have multiple test suites, they can run in parallel (up to 6 at the same time).
We chose 6 because our CI environment has 8 cores and we need some head room for other things.

For core tests, the speed improvements is huge. With some more tuning
(running slow test first/split test suites) the performance win will be even higher. We are gonna quantify our
wins in https://phabricator.wikimedia.org/T417654

The full change list in this release:
* Fix XVFB handling outside of CI (T417752)
* Increase default max instances to 6 (T414904)
* Run maxInstances 6 and headless only in CI by default (T417732)
* Start one xvfb per maxInstance (NodeJS instance) (T344754)
* Take screenshot only on failures (T416704)
* Use headless as default (T411784)

## 6.2.0 / 2026-02-12
This release cleanup the default configuration code, add a helper method for dirname.
The last change moves out settings for video and headless to make it easier to users
to actually change those.

* Add Util.dirname() helper for ESM compatibility (T407636)
* Move Chrome setting/options out of configuration to new file (T414672)
* Move process handlers out of configuration to new file (T414672)
* Move video and headless configuration out of capabilities (T415057)

## 6.1.0 / 2026-01-08

The changes in 6.1.0 has a couple of focus areas.

### Performance
We decrease the overhead of using FFMPEG by following bsest practices. In CI
this will makes tests 12-13% faster and use 26% less of CPU time.

We also made it possible to disable video recording and run tests as true headless
in CI using configuration to make it easier to measure performance wins by turning
off video recordings.

* Add configuration to enable/disable video recording (T410594)
* Decrease FFmpeg overhead (T408328)
* Make it possible to configure the --headless flag (T410607)

### Prometheus
There's been ongoing work to get Promethues metrics from Jenkins CI. The epic for that work
is T412714. In this release there are a couple of bug fixes and some new metrics.

* Add specific flaky metric for Prometheus (T413062)
* Make it possible to collect average run time per project (T413064)
* Cleanup how to handle numbers for duration (T412681)
* Only write Promethues metrics if test runs (T407831)

### Make our tool better
There's a change here where we set the screen size. This is good because we never did that before
and using headdless vs not using headless used different screensizes. We also added logging of
browser/system information at startup to make it easier to spot differences when we run tests.

* Set browser size to 1280x1024 (T409439)
* Disable enable automation switch (T403827)
* Log browser information (T411071)
* Log system information on startup (T411069)

### Documentation
We updated documentation and code documentation.

* Make it clear that the API calls do not follow redirects (T408087)
* Document how dev-shm is used in CI (T408360)
* Update mwbot update example (T406489)
* Add documentation link to Chrome cli parameters (T408320)

### Cleanup
* Remove code from beforeSession() hook that is no longer needed (T355556)
* Remove daily beta test for webdriver.io (T410889)
* Remove unused code in Prometheus exporter (T412681)

### Misc
* Log out via special page T411278

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
