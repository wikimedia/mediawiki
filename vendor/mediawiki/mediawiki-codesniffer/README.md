# MediaWiki coding conventions #

## Abstract ##
This project implements a set of rules for use with [PHP CodeSniffer](https://pear.php.net/package/PHP_CodeSniffer).

See [MediaWiki conventions](https://www.mediawiki.org/wiki/Manual:Coding_conventions/PHP) on our wiki. :-)


## How to install ##
1. Create a composer.json which adds this project as a dependency:

		{
			"require-dev": {
				"mediawiki/mediawiki-codesniffer": "0.3.0"
			},
			"scripts": {
				"test": [
					"phpcs --standard=vendor/mediawiki/mediawiki-codesniffer/MediaWiki --extensions=php,php5,inc --ignore=vendor -p ."
				]
			}
		}

2. Install: `composer update`

3. Run: `composer test`

4. Fix & commit!

Note that for most MediaWiki projects, we'd also recommend adding a PHP linter to your `composer.json` – see the [full documentation](https://www.mediawiki.org/wiki/Continuous_integration/Entry_points#PHP) for more details.


## TODO ##
* Migrate the old code-utils/check-vars.php
