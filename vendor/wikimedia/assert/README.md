This package provides an alternative to PHP's `assert()` that allows for an simple and reliable way
to check preconditions and postconditions in PHP code. It was proposed [as a MediaWiki RFC](https://www.mediawiki.org/wiki/Requests_for_comment/Assert),
but is completely generic and can be used by any PHP program or library. It is published under the
MIT license, see the COPYING file.

Usage
-------

The Assert class provides several static methods for checking various kinds of assertions.
The most common kind is to check the type of a parameter, typically in a constructor or a
setter method:

    function setFoo( $foo ) {
        Assert::parameterType( 'integer', $foo, 'foo' );
        Assert::parameter( $foo > 0, 'foo', 'must be greater than 0' );
    }
    
    function __construct( $bar, array $bazz ) {
        Assert::parameterType( 'Me\MyApp\SomeClass', $bar );
        Assert::parameterElementType( 'int', $bazz );
    }

Checking parameters, or other assertions such as pre- or postconditions, is not recommended for
performance critical regions of the code, since evaluating expressions and calling the assertion
functions costs time.


Rationale
-----------
The background of this proposal is the recurring discussions about whether PHP's `assert()`
can and should be used in MediaWiki code. Two relevant threads:
* [Using PHP's assert in MediaWiki code](http://www.gossamer-threads.com/lists/wiki/wikitech/275737)
* [Is assert() allowed?](http://www.gossamer-threads.com/lists/wiki/wikitech/378676)

The outcome appears to be that
* assertions are generally a good way to improve code quality
* but PHP's ''assert()'' is broken by design

Following a [suggestion by Tim Starling](http://www.gossamer-threads.com/lists/wiki/wikitech/378815#378815),
this package provides an alternative to PHP's built in `assert()`.

[![Build Status](https://secure.travis-ci.org/wmde/Assert.svg)](https://travis-ci.org/wmde/Assert)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wmde/Assert/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wmde/Assert/?branch=master)
