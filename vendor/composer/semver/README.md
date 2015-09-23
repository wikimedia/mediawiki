composer/semver
===============

Semver library that offers utilities, version constraint parsing and validation.

Originally written as part of [composer/composer](https://github.com/composer/composer),
now extracted and made available as a stand-alone library.

[![Build Status](https://travis-ci.org/composer/semver.svg?branch=master)](https://travis-ci.org/composer/semver)

Installation
------------

Install the latest version with:

```bash
$ composer require composer/semver
```

Requirements
------------

* PHP 5.3.2 is required but using the latest version of PHP is highly recommended.

Basic usage
-----------

The `Composer\Semver\Comparator` class provides the following high-level
functions for comparing versions:

* greaterThan
* greaterThanOrEqualTo
* lessThan
* lessThanOrEqualTo
* equalTo
* notEqualTo

Each function takes two version strings as arguments. For example:

```php
use Composer\Semver\Comparator;

Comparator::greaterThan('1.25.0', '1.24.0'); // 1.25.0 > 1.24.0
```


License
-------

composer/semver is licensed under the MIT License, see the LICENSE file for details.
