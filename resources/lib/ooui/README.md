[![npm](https://img.shields.io/npm/v/oojs-ui.svg?style=flat)](https://www.npmjs.com/package/oojs-ui) [![Packagist](https://img.shields.io/packagist/v/oojs/oojs-ui.svg?style=flat)](https://packagist.org/packages/oojs/oojs-ui)

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

<pre lang="bash">
$ npm install oojs-ui
</pre>

Once installed, include the following scripts and styles to get started:

<pre lang="html">
<script src="node_modules/jquery/dist/jquery.min.js"></script>
<script src="node_modules/oojs/dist/oojs.min.js"></script>

<script src="node_modules/oojs-ui/dist/oojs-ui.min.js"></script>
<script src="node_modules/oojs-ui/dist/oojs-ui-wikimediaui.min.js"></script>
<link rel="stylesheet" href="node_modules/oojs-ui/dist/oojs-ui-wikimediaui.min.css">
</pre>


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

Release process:
<pre lang="bash">

    $ cd path/to/oojs-ui/
    $ git remote update
    $ git checkout -B release -t origin/master

    # Clean install npm dependencies. Update Composer dependencies. And ensure tests pass
    $ npm ci && composer update && npm test && composer test

    # Update release notes
    # Copy the resulting list into a new section at the top of History.md and edit
    # into five sub-sections, in order:
    # * ### Breaking changes
    # * ### Deprecations
    # * ### Features
    # * ### Styles
    # * ### Code
    $ git log --format='* %s (%aN)' --no-merges v$(node -e 'console.log(require("./package").version);')...HEAD | grep -v "Localisation updates from" | sort
    $ edit History.md

    # Generate the list of Phabricator tasks
    # Copy the resulting list and save it for later. Paste it into the commit message when updating MediaWiki.
    $ git log --pretty=format:%b v$(node -e 'console.log(require("./package").version);')...HEAD | grep Bug: | sort | uniq

    # Update the version number (change 'patch' to 'minor' if you've made breaking changes):
    $ npm version patch --git-tag-version=false

    # Commit the release and submit to Gerrit
    $ git add -p
    $ git commit -m "Tag v$(node -e 'console.log(require("./package").version);')"
    $ git review

    # After merging this commit, push the tag and publish to NPM:
    $ git remote update
    $ git checkout origin/master
    $ git tag "v$(node -e 'console.log(require("./package").version);')"
    $ npm run publish-build && git push --tags && npm publish

    # Update the mediawiki/vendor repo:
    $ cd path/to/mediawiki-vendor
    # Replace 1.2.34 with the version number of the new release
    # See the README.md in the mediawiki/vendor repo for info on which composer version you must use
    # and how to run composer through Docker if you have the wrong version
    $ composer require oojs/oojs-ui 1.2.34 --no-update
    $ composer update --no-dev
    $ git add oojs/oojs-ui
    # Commit these changes with the following commit message (example: https://gerrit.wikimedia.org/r/c/mediawiki/vendor/+/813629 )
    # Update OOUI to v1.2.34
    #
    #  Release notes: https://gerrit.wikimedia.org/g/oojs/ui/+/v1.2.34/History.md"
    $ git commit -a
    $ git review
    # Look at the commit message to get the Change-Id. Copy the Change-Id and save it for later.
    # You will need it for the Depends-On: line in the commit message when updating MediaWiki.
    $ git show --stat

    # Update the mediawiki repo:
    $ cd path/to/mediawiki
    # Update the version number of oojs/oojs-ui
    $ edit composer.json
    # Update or add the "Updated OOUI from v1.2.24 to v1.2.34" entry in the "Changed external libraries" section
    $ edit RELEASE-NOTES-1.NN
    # Update the version: field and the version number in the URL for ooui
    $ edit resources/lib/foreign-resources.yaml
    # Compute the new integrity hash
    $ php maintenance/run.php manageForeignResources make-sri ooui
    # Replace the integrity: field with this new hash
    $ edit resources/lib/foreign-resources.yaml
    $ php maintenance/run.php manageForeignResources update ooui
    $ git add resources/lib/ooui
    # Commit these changes with the following commit message (example: https://gerrit.wikimedia.org/r/c/mediawiki/core/+/813630 )
    # Update OOUI to v1.2.34
    #
    #  Release notes: https://gerrit.wikimedia.org/g/oojs/ui/+/v1.2.34/History.md"
    #
    # [Insert the list of Bug: lines you saved before]
    # Depends-On: [Insert the Change-Id of the vendor repo commit]
    $ git commit -a
    $ git review

</pre>
