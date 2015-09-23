[![Latest Stable Version]](https://packagist.org/packages/wikimedia/cdb) [![License]](https://packagist.org/packages/wikimedia/cdb)

CDB functions for PHP
=====================

[CDB][], short for "constant database", refers to a very fast and highly
reliable database system which uses a simple file with key value pairs. This
library wraps the CDB functionality exposed in PHP via the `dba_*` functions.
In cases where `dba_*` functions are not present or are not compiled with CDB
support, a pure-PHP implementation is provided for falling back.

Additional documentation about the library can be found on
[MediaWiki.org](https://www.mediawiki.org/wiki/CDB).


Usage
-----

    // Reading a CDB file
    $cdb = \Cdb\Reader::open( 'db.cdb' );
    $foo = $cdb->get( 'somekey' );

    // Writing to a CDB file
    $cdb = \Cdb\Writer::open( 'anotherdb.cdb' );
    $cdb->set( 'somekey', $foo );


Running tests
-------------

    composer install --prefer-dist
    composer test


History
-------

This library was first introduced in [MediaWiki 1.16][] ([r52203][]). It was
split out of the MediaWiki codebase and published as an independent library
during the [MediaWiki 1.25][] development cycle.


---
[CDB]: https://en.wikipedia.org/wiki/cdb_(software)
[MediaWiki 1.16]: https://www.mediawiki.org/wiki/MediaWiki_1.16
[r52203]: https://www.mediawiki.org/wiki/Special:Code/MediaWiki/52203
[MediaWiki 1.25]: https://www.mediawiki.org/wiki/MediaWiki_1.25
[Latest Stable Version]: https://poser.pugx.org/wikimedia/cdb/v/stable.svg
[License]: https://poser.pugx.org/wikimedia/cdb/license.svg
