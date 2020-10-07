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


We use [Gerrit](https://gerrit.wikimedia.org/) for code review, and [Phabricator](https://phabricator.wikimedia.org) to track issues. To contribute patches or join discussions all you need is a [developer account](https://wikitech.wikimedia.org/w/index.php?title=Special:CreateAccount&returnto=Help%3AGetting+Started).

* If you've found a bug, or wish to request a feature [raise a ticket on Phabricator](https://phabricator.wikimedia.org/maniphest/task/edit/form/1/?projects=PHID-PROJ-dgmoevjqeqlerleqzzx5).
* To submit your patch, follow [the "getting started" quick-guide](https://www.mediawiki.org/wiki/Gerrit/Getting_started). We try to review patches within a week.
* We automatically lint and style-check changes to JavaScript, PHP, LESS/CSS, Ruby and JSON files. You can test these yourself with `npm test` and `composer test` locally before pushing changes. SVG files should be squashed in advance of committing with [SVGO](https://github.com/svg/svgo) using `svgo --pretty --disable=removeXMLProcInst --disable=cleanupIDs <filename>`.

A new version of the library is released most weeks on Tuesdays.

Community
---------

Get updates, ask questions and join the discussion with maintainers and contributors:

* Join the Wikimedia Developers mailing list, [wikitech-l](https://lists.wikimedia.org/mailman/listinfo/wikitech-l).
* Chat with the maintainers on `#wikimedia-dev` on `irc.freenode.net`.
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

    # Ensure tests pass
    $ npm install && composer update && npm test && composer test

    # Avoid using "npm version patch" because that creates
    # both a commit and a tag, and we shouldn't tag until after
    # the commit is merged.

    # Update release notes
    # Copy the resulting list into a new section at the top of History.md and edit
    # into five sub-sections, in order:
    # * Breaking changes
    # * Deprecations
    # * Features
    # * Styles
    # * Code
    $ git log --format='* %s (%aN)' --no-merges --reverse v$(node -e 'console.log(require("./package.json").version);')...HEAD | grep -v "Localisation updates from" | sort
    $ edit History.md

    # Update the version number
    $ edit package.json

    $ git add -p
    $ git commit -m "Tag vX.X.X"
    $ git review

    # After merging:
    $ git remote update
    $ git checkout origin/master
    $ git tag "vX.X.X"
    $ npm run publish-build && git push --tags && npm publish

</pre>
