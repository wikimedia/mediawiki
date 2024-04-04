OOUI
=================

OOUI is a component-based JavaScript UI library. Key features:

* Common widgets, layouts, and dialogs
* Classes, elements, and mixins to create custom interfaces
* Internationalization and localization, like right-to-left (RTL) languages support
* Theme-ability
* Built-in icons
* Accessibility features

It is the standard library for Web products at the Wikimedia Foundation, having been originally created for use by [VisualEditor](https://www.mediawiki.org/wiki/VisualEditor).


Quick start
----------

The library is available on [npm](https://www.npmjs.com/package/oojs-ui). To install:

```bash
$ npm install oojs-ui
```

Once installed, include the following scripts and styles to get started:

```html
<script src="node_modules/jquery/dist/jquery.min.js"></script>
<script src="node_modules/oojs/dist/oojs.min.js"></script>

<script src="node_modules/oojs-ui/dist/oojs-ui.min.js"></script>
<script src="node_modules/oojs-ui/dist/oojs-ui-wikimediaui.min.js"></script>
<link rel="stylesheet" href="node_modules/oojs-ui/dist/oojs-ui-wikimediaui.min.css">
```


Loading the library
-------------------

While the distribution directory is chock-full of files, you will normally load only the following three:

* `oojs-ui.js`, containing the full library;
* One of `oojs-ui-wikimediaui.css` or `oojs-ui-apex.css`, containing theme-specific styles; and
* One of `oojs-ui-wikimediaui.js` or  `oojs-ui-apex.js`, containing theme-specific code

You can load additional icon packs from files named `oojs-ui-wikimediaui-icons-*.css` or `oojs-ui-apex-icons-*.css`.

The remaining files make it possible to load only parts of the whole library.

Furthermore, every CSS file has a right-to-left (RTL) version available, to be used on pages using right-to-left languages if your environment doesn't automatically flip them as needed.


Issue tracker
-------------

Found a bug or missing feature? Please report it in our [issue tracker Phabricator](https://phabricator.wikimedia.org/maniphest/task/edit/form/1/?projects=PHID-PROJ-dgmoevjqeqlerleqzzx5)!


Contributing
------------

We are always delighted when people contribute patches. To setup your development environment:


1. Clone the repo: `$ git clone https://gerrit.wikimedia.org/r/oojs/ui oojs-ui`

2. Move into the library directory:<br>`$ cd oojs-ui`

3. Install [composer](https://getcomposer.org/download/) and make sure running `composer` will execute it (*e.g.* add it to `$PATH` in POSIX environments).

4. Install dev dependencies:<br>`$ npm install`

5. Build the library (you can alternatively use `grunt quick-build` if you don't need to rebuild the PNGs):<br>`$ grunt build`

6. You can see a suite of demos in `/demos` by executing:<br>`$ npm run-script demos`

7. You can also copy the distribution files from the dist directory into your project.

8. You can start a local web server by running `php -S localhost:80` in your root dir.

9. You can navigate to http://localhost/tests/ to run the tests locally in your browser.

We use [Gerrit](https://gerrit.wikimedia.org/) for code review, and [Phabricator](https://phabricator.wikimedia.org) to track issues. To contribute patches or join discussions all you need is a [developer account](https://wikitech.wikimedia.org/w/index.php?title=Special:CreateAccount&returnto=Help%3AGetting+Started).

* If you've found a bug, or wish to request a feature [raise a ticket on Phabricator](https://phabricator.wikimedia.org/maniphest/task/edit/form/1/?projects=PHID-PROJ-dgmoevjqeqlerleqzzx5).
* To submit your patch, follow [the "getting started" quick-guide](https://www.mediawiki.org/wiki/Gerrit/Getting_started). We try to review patches within a week.
* We automatically lint and style-check changes to JavaScript, PHP, LESS/CSS, Ruby and JSON files. You can test these yourself with `npm test` and `composer test` locally before pushing changes. SVG files should be squashed in advance of committing with [SVGO](https://github.com/svg/svgo) using `svgo --pretty --disable=removeXMLProcInst --disable=cleanupIDs <filename>`.

A new version of the library is released most weeks on Tuesdays.

Community
---------

Get updates, ask questions and join the discussion with maintainers and contributors:

* Join the Wikimedia Developers mailing list, [wikitech-l](https://lists.wikimedia.org/mailman/listinfo/wikitech-l).
* Chat with the maintainers on `#wikimedia-dev` on `irc.libera.chat`.
* Ask questions on [StackOverflow](https://stackoverflow.com/tags/oojs-ui/info).
* Watchlist the [documentation](https://www.mediawiki.org/wiki/OOUI) on MediaWiki to stay updated.


Versioning
----------

We use the [Semantic Versioning guidelines](http://semver.org/).

Releases will be numbered in the following format:

`<major>.<minor>.<patch>`


Release
----------

### Prerequisites
- [Create an NPM account](https://www.npmjs.com/signup), if you don't have one already
- Verify that you can [log into your NPM account](https://www.npmjs.com/login)
- Verify that you are [listed as a maintainer](https://www.npmjs.com/package/oojs-ui/access)
  of the oojs-ui package. If not, ask an existing maintainer to add you.
- Make sure that you have two-factor authentication (2FA) set up.
- Run `npm login` and follow the steps. You should only need to do this once on each computer.
  If you're not sure if you've already done this, you can run `npm whoami`; if it prints your
  NPM username, you're already logged in.
- Clone the mediawiki/vendor repository: https://gerrit.wikimedia.org/r/admin/repos/mediawiki/vendor,general

### Prepare and submit the release commit
From the root of this repository, update master and check out a new `release` branch:
```
$ git remote update
$ git checkout -B release -t origin/master
```

Clean-install npm dependencies, update Composer dependencies, and ensure tests pass:
```
$ npm ci && composer update && npm test && composer test
```

Generate a list of commits that are part of this release:
```
$ git log --format='* %s (%aN)' --no-merges v$(node -e 'console.log(require("./package").version);')...HEAD | grep -v "Localisation updates from" | sort
```

In History.md, add a new heading for this version and date. Copy the list of commits into the new
section and sort into five sub-sections, in order, omitting any sub-section that has no commits:
```
### Breaking changes
### Deprecations
### Features
### Styles
### Code
```

Generate the list of Phabricator tasks for this realease. Copy the resulting list and save it for
later. In a later step, you will add it to the commit message of the MediaWiki core commit.
```
$ git log --pretty=format:%b v$(node -e 'console.log(require("./package").version);')...HEAD | grep Bug: | sort | uniq
```

Update the version number (in the following command, change 'patch' to 'minor' if you've made
breaking changes):
```
$ npm version patch --git-tag-version=false
```

Commit the release and submit to Gerrit. Note that if there is a Phabricator task associated with
the release, you should edit the commit to add the bug number before running `git review`.
```
$ git add -a
$ git commit -m "Tag v$(node -e 'console.log(require("./package").version);')"
$ git review
```

### Publish the tag and push to NPM
After the tag commit is merged in this repo, push the tag and publish to NPM:
```
$ git remote update
$ git checkout origin/master
$ git tag "v$(node -e 'console.log(require("./package").version);')"
$ npm run publish-build && git push --tags && npm publish
```

### Update the mediawiki/vendor repo
In your local mediawiki/vendor repo, point composer to the new version and pull in the updated
vendor files:
```
# Replace 1.2.34 with the version number of the new release
$ composer require oojs/oojs-ui 1.2.34 --no-update
$ composer update --no-dev
```

Then commit the changes with the following commit message, replacing 1.2.34 with the new OOUI
version number (example: https://gerrit.wikimedia.org/r/c/mediawiki/vendor/+/813629).
```
$ git add -a
$ git commit
```

Commit message format:
```
Update OOUI to v1.2.34

  Release notes: https://gerrit.wikimedia.org/g/oojs/ui/+/v1.2.34/History.md"
```

Push this to gerrit. Take note of the Change-Id in the commit message. Copy it and save it for
later. You will need it for the Depends-On: line in the commit message when updating MediaWiki.
```
$ git review
# Show the last commit
$ git show --stat
```

### Update the MediaWiki core repo

In your local MediaWiki core repo, open `composer.json` and update the version number of
`oojs/oojs-ui` to the new version number.

Open `RELEASE-NOTES-1.NN`. If there is already a list item about OOUI, update the latest version
number. For example, if there is a list item that says "Updated OOUI from v1.2.0 to v1.2.33",
update the latter version number fo `v1.2.34`. If there isn't a list item about OOUI yet, add one
in the `Changed external libraries` section.

Open `resources/lib/foreign-resources.yaml`. For the OOUI listing, change the `version` and the
`src` URL to use the new version number. Compute the new integrity hash:

```
$ php maintenance/run.php manageForeignResources make-sri ooui
# Or if you're running Docker...
$ docker compose exec mediawiki php maintenance/run.php manageForeignResources make-sri ooui
```

Then update the OOUI library files:
```
$ php maintenance/run.php manageForeignResources update ooui
# Or if you're running Docker...
$ docker compose exec mediawiki php maintenance/run.php manageForeignResources update ooui
```

Then commit the changes with the following commit message, replacing 1.2.34 with the new OOUI
version number:
```
$ git add -a
$ git commit
```

Commit message format, where the list of bugs is the list you generated during the OOUI tag step,
and Depends-On is set to the Change-Id of the mediawiki/vendor commit:
```
Update OOUI to v1.2.34

  Release notes: https://gerrit.wikimedia.org/g/oojs/ui/+/v1.2.34/History.md"

Bug: T123456
Bug: T234567
Depends-On: I12345678901234567890
```

Then push that commit to gerrit:
```
git review
```

### Update the VisualEditor/VisualEditor repo
In your local VisualEditor/VisualEditor repo, run the script to create a commit updating the local
copy of OOUI, and push the commit to Gerrit:

```
$ ./bin/update-ooui.sh
$ git review
```
