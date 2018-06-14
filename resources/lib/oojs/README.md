[![npm](https://img.shields.io/npm/v/oojs.svg?style=flat)](https://www.npmjs.com/package/oojs) [![David](https://img.shields.io/david/dev/wikimedia/oojs.svg?style=flat)](https://david-dm.org/wikimedia/oojs#info=devDependencies)

OOjs
=================

OOjs is a JavaScript library for working with objects.

Key features include inheritance, mixins and utilities for working with objects.

<pre lang="javascript">
/* Example */
( function ( oo ) {
    function Animal() {}
    function Magic() {}
    function Unicorn() {
        Animal.call( this );
        Magic.call( this );
    }
    oo.inheritClass( Unicorn, Animal );
    oo.mixinClass( Unicorn, Magic );
}( OO ) );
</pre>

Quick start
----------

This library is available as an [npm](https://npmjs.org/) package! Install it right away:
<pre lang="bash">
npm install oojs
</pre>

Or clone the repo, `git clone https://phabricator.wikimedia.org/diffusion/GOJS/oojs.git`.

ECMAScript 5
----------

OOjs requires a modern ECMAScript 5 environment. It is not necessarily compatible with ES3 engines (such as for IE 6-8). For ES3 environments, the old 1.x releases are available but not recommended.

jQuery
----------

If your project uses jQuery, use the optimised `oojs.jquery.js` build instead.

This build assumes jQuery is present and omits various chunks of code in favour of references to jQuery.

jQuery 3.0.0 or higher is required.

Versioning
----------

We use the Semantic Versioning guidelines as much as possible.

Releases will be numbered in the following format:

`<major>.<minor>.<patch>`

For more information on SemVer, please visit http://semver.org/.

Bug tracker
-----------

Found a bug? Please report it in the [issue tracker](https://phabricator.wikimedia.org/maniphest/task/edit/form/1/?projects=OOjs)!

Release
----------

Release process:
<pre lang="bash">
$ cd path/to/oojs/
$ git remote update
$ git checkout -B release -t origin/master

# Ensure tests pass
$ npm install-test

# Avoid using "npm version patch" because that creates
# both a commit and a tag, and we shouldn't tag until after
# the commit is merged.

# Update release notes
# Copy the resulting list into a new section on History.md
$ git log --format='* %s (%aN)' --no-merges --reverse v$(node -e 'console.log(require("./package.json").version);')...HEAD
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
