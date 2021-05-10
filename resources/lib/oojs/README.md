[![npm](https://img.shields.io/npm/v/oojs.svg?style=flat)](https://www.npmjs.com/package/oojs)

OOjs
=================

OOjs is a JavaScript library for working with objects.

Key features include inheritance, mixins and utilities for working with objects.

<pre lang="javascript">
/* Example */
function Animal() {}
function Magic() {}
function Unicorn() {
    Animal.call( this );
    Magic.call( this );
}
OO.inheritClass( Unicorn, Animal );
OO.mixinClass( Unicorn, Magic );
</pre>

Quick start
----------

This library is available as an [npm](https://npmjs.org/) package! Install it right away:
<pre lang="bash">
npm install oojs
</pre>

Or clone the repo, `git clone https://gerrit.wikimedia.org/r/oojs/core`.

ECMAScript 5
----------

OOjs requires a modern ECMAScript 5 environment. It is not necessarily compatible with ES3 engines (such as for IE 6-8). For ES3 environments, the old 1.x releases are available but not recommended.

Versioning
----------

We use the Semantic Versioning guidelines as much as possible.

Releases will be numbered in the following format:

`<major>.<minor>.<patch>`

For more information on SemVer, please visit http://semver.org/.

Bug tracker
-----------

Found a bug? Please report it in the [issue tracker](https://phabricator.wikimedia.org/maniphest/task/edit/form/1/?projects=OOjs)!
