[![Latest Stable Version]](https://packagist.org/packages/mediawiki/at-ease) [![License]](https://packagist.org/packages/mediawiki/at-ease)

at-ease
=======

at-ease is a PHP library that provides a safe alternative to PHP's
[@ error control operator][].

`@` is broken when `E_STRICT` is enabled and it causes an unlogged,
unexplained error if there is a fatal, which is hard to support. The proper
method of handling errors is to actually handle the errors. For example, if
you are thinking of using an error suppression operator to suppress an invalid
array index warning, you should instead perform an `isset()` check on the
array index before trying to access it. When possible, always prevent PHP
errors rather than catching and handling them afterward. It makes the code
more understandable and avoids dealing with slow error suppression methods.

However, there are some cases where warnings are inevitable, even if you check
beforehand, like when accessing files. You can check that the file exists by
using `file_exists()` and `is_readable()`, but the file could have been
deleted by the time you go to read it. In that case, you can use this library
to suppress the warnings and prevent PHP from being noisy.


Usage
-----

    // Suppress warnings in a block of code:
    \MediaWiki\suppressWarnings();
    $content = file_get_contents( 'foobar.txt' );
    \MediaWiki\restoreWarnings();


    // ..or in a callback function:
    \MediaWiki\quietCall( 'file_get_contents', 'foobar.txt' );


Running tests
-------------

    composer install --prefer-dist
    composer test


History
-------

This library was first introduced in [MediaWiki 1.3][] ([r4261][]). It was
split out of the MediaWiki codebase and published as an independent library
during the [MediaWiki 1.26][] development cycle.


---
[@ error control operator]: https://php.net/manual/en/language.operators.errorcontrol.php
[MediaWiki 1.3]: https://www.mediawiki.org/wiki/MediaWiki_1.3
[r4261]: https://www.mediawiki.org/wiki/Special:Code/MediaWiki/r4261
[MediaWiki 1.26]: https://www.mediawiki.org/wiki/MediaWiki_1.26
[Latest Stable Version]: https://poser.pugx.org/mediawiki/at-ease/v/stable.svg
[License]: https://poser.pugx.org/mediawiki/at-ease/license.svg
