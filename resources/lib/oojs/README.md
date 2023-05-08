[![npm](https://img.shields.io/npm/v/oojs.svg?style=flat)](https://www.npmjs.com/package/oojs)

# OOjs

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

## Quick start

This library is available as an [npm](https://npmjs.org/) package! Install it right away:

<pre lang="bash">
npm install oojs
</pre>

Or clone the repo, `git clone https://gerrit.wikimedia.org/r/oojs/core`.

## Browser support

We officially support these browsers, aligned with [MediaWiki's compatibility guideline](https://www.mediawiki.org/wiki/Compatibility#Browsers):

* Firefox: last three years (Firefox 78+, 2020)
* Chrome: last three years (Chrome 80+, 2020)
* Edge: last three years (Edge 80+, 2020)
* Opera: last thee years (Opera 67+, 2020)
* iOS: 11.3+

OOjs requires a modern ES2015 (ECMAScript 6) environment. To support older browsers with ECMAScript 5 engines (such as IE 11), use the last OOjs 6.x release.

## Bug tracker

Found a bug? Please report it in the [issue tracker](https://phabricator.wikimedia.org/maniphest/task/edit/form/1/?projects=OOjs)!
