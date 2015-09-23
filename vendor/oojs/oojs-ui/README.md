[![npm](https://img.shields.io/npm/v/oojs-ui.svg?style=flat)](https://www.npmjs.com/package/oojs-ui) [![Packagist](https://img.shields.io/packagist/v/oojs/oojs-ui.svg?style=flat)](https://packagist.org/packages/oojs/oojs-ui) [![David](https://img.shields.io/david/dev/wikimedia/oojs-ui.svg?style=flat)](https://david-dm.org/wikimedia/oojs-ui#info=devDependencies)

OOjs UI
=================

OOjs UI is a modern JavaScript UI toolkit for browsers. It provides a library of common widgets, layouts and windows that are ready to use, as well as many foundational classes for constructing custom user interfaces. The library was originally created for use by [VisualEditor](https://www.mediawiki.org/wiki/VisualEditor), which uses it for its entire user interface, and is now completely independent, and more useful and convenient for other use cases.

Quick start
----------

This library is available as an [npm](https://npmjs.org/) package! Install it right away:
<pre lang="bash">
npm install oojs-ui
</pre>

If you don't want to use npm, you can:

1. Clone the repo, `git clone https://git.wikimedia.org/git/oojs/ui.git`.

1. Install Grunt command-line utility:<br/>`$ npm install -g grunt-cli`

1. Install dev dependencies and build the distribution files:<br/>`$ npm install`

1. You can now copy the distribution files from the dist directory into your project.

1. You can see a suite of demos in `/demos` by executing:<br/>`$ npm run-script demos`


Versioning
----------

We use the Semantic Versioning guidelines as much as possible.

Releases will be numbered in the following format:

`<major>.<minor>.<patch>`

For more information on SemVer, please visit http://semver.org/.


Issue tracker
-------------

Found a bug or missing feature? Please report it in the [issue tracker](https://phabricator.wikimedia.org/maniphest/task/create/?projects=PHID-PROJ-dgmoevjqeqlerleqzzx5)!


Release
----------

Release process:
<pre lang="bash">
$ cd path/to/oojs-ui/
$ git remote update
$ git checkout -B release -t origin/master

# Ensure tests pass
$ npm install && composer update && npm test

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
$ git push --tags
$ npm publish
</pre>
