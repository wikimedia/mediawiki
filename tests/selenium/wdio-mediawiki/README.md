# wdio-mediawiki

A plugin for [WebdriverIO](https://webdriver.io) providing utilities to simplify testing of MediaWiki features.

## Getting Started

### Page

The `Page` class is a base class for following the [Page Objects Pattern](https://webdriver.io/docs/pageobjects).

* `openTitle( title [, Object query [, string fragment ] ] )`

The convention is for implementations to extend this class and provide an `open()` method
that calls `super.openTitle()`, as well as add various getters for elements on the page.

See [BlankPage](./BlankPage.js) and [specs/BlankPage](./specs/BlankPage.js) for an example.

### Api

Utilities to interact with the MediaWiki API. Uses the [mwbot](https://github.com/gesinn-it-pub/mwbot) library.

Actions are performed logged-in using `browser.options.capabilities[ 'mw:user' ]` and `browser.options.capabilities[ 'mw:pwd' ]`,
which typically come from `MEDIAWIKI_USER` and `MEDIAWIKI_PASSWORD` environment variables.

* `bot([string username [, string password [, string baseUrl ] ] ])`
* `createAccount(MWBot bot, string username, string password)`
* `blockUser(MWBot bot, [ string username [, string expiry ] ])`
* `unblockUser(MWBot bot, [ string username ])`
* `addUserToGroup(MWBot bot, string username, string groupName)`

Example:

```js
bot = await Api.bot();
await bot.edit( 'Some page', 'Some initial content' );
await bot.edit( 'Some page', 'Some other content', 'Optional edit reason here' );
await bot.delete( 'Some page', 'Some deletion reason here' );
```

### RunJobs

Use the static `RunJobs.run()` method to ensure that any queued jobs are executed before
making assertions that depend on its outcome.

### Util

`Util` is a collection of popular utility methods.

* `getTestString([ string prefix ])`
* `isTargetNotWikitext(string target)`
* `waitForModuleState(string moduleName [, string moduleStatus [, number timeout ] ])`

## Versioning

This package follows [Semantic Versioning guidelines](https://semver.org) for its releases. In
particular, its major version must be bumped when compatibility is removed for a previous of
MediaWiki.

It is the expectation that this module will only support a single version of MediaWiki at any
given time, and that tests in older branches of MediaWiki-related projects naturally use the older
release line of this package.

In order to allow for smooth and decentralised upgrades, it is recommended that the only type of
breaking change made to this package is a change that removes something. Thus, in order to change
something, it must either be backwards-compatible, or must be introduced as a new method that
co-exists with its deprecated equivalent for at least one release.

## Issue tracker

Please report issues to [Phabricator](https://phabricator.wikimedia.org/tag/mediawiki-core-tests).

## Contributing

This module is maintained in the MediaWiki core repository and published from there as a
package to npmjs.org. To simplify development and to ensure changes are verified
automatically, MediaWiki core itself uses this module directly from the working copy
using [npm Local Paths](https://docs.npmjs.com/cli/v10/configuring-npm/package-json#local-paths).
