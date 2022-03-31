# MediaWiki PHPUnit tests

**WARNING**: Integration tests may be destructive and alter or remove parts of your local database. We try to use temporary tables where possible, but you must **never run tests on a production server** or on a wiki where you don't want to lose data.

## Running tests

If you haven't already, run `composer update` (specifically without `--no-dev`) in the MediaWiki core directory. This will install [PHPUnit](https://phpunit.de/).

To read about how to run specific tests, refer to:

https://www.mediawiki.org/wiki/Manual:PHP_unit_testing/Running_the_tests

## Writing tests

A guide to writing PHPUnit tests for MediaWiki can be found at:

https://www.mediawiki.org/wiki/Manual:PHP_unit_testing

