[![Latest Stable Version](https://img.shields.io/packagist/v/wikimedia/composer-merge-plugin.svg?style=flat)](https://packagist.org/packages/wikimedia/composer-merge-plugin) [![License](https://img.shields.io/packagist/l/wikimedia/composer-merge-plugin.svg?style=flat)](https://github.com/wikimedia/composer-merge-plugin/blob/master/LICENSE)
[![Build Status](https://img.shields.io/travis/wikimedia/composer-merge-plugin.svg?style=flat)](https://travis-ci.org/wikimedia/composer-merge-plugin)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/wikimedia/composer-merge-plugin/master.svg?style=flat)](https://scrutinizer-ci.com/g/wikimedia/composer-merge-plugin/?branch=master)

Composer Merge Plugin
=====================

Merge one or more additional composer.json files at runtime.

Installation
------------
```
$ composer require wikimedia/composer-merge-plugin
```

Usage
-----

```
{
    "require": {
        "wikimedia/composer-merge-plugin": "dev-master"
    },
    "extra": {
        "merge-plugin": {
            "include": [
                "composer.local.json",
                "extensions/*/composer.json"
            ]
        }
    }
}
```

The `include` key can specify either a single value or an array of values.
Each value is treated as a glob() pattern identifying additional composer.json
style configuration files to merge into the configuration for the current
Composer execution. By default the merge plugin is recursive, if an included
file also has a "merge-plugin" section it will also be processed. This 
functionality can be disabled by setting `"recurse": false` inside the 
"merge-plugin" section.

The "require", "require-dev", "repositories" and "suggest" sections of the
found configuration files will be merged into the root package configuration
as though they were directly included in the top-level composer.json file.

Running tests
-------------
```
$ composer install
$ composer test
```

Contributing
------------
Bug, feature requests and other issues should be reported to the [GitHub
project]. We accept code and documentation contributions via Pull Requests on
GitHub as well.

- [PSR-2 Coding Standard][] is used by the project. The included test
  configuration uses [PHP Code Sniffer][] to validate the conventions.
- Tests are encouraged. Our test coverage isn't perfect but we'd like it to
  get better rather than worse, so please try to include tests with your
  changes.
- Keep the documentation up to date. Make sure `README.md` and other
  relevant documentation is kept up to date with your changes.
- One pull request per feature. Try to keep your changes focused on solving
  a single problem. This will make it easier for us to review the change and
  easier for you to make sure you have updated the necessary tests and
  documentation.

License
-------
Composer Merge plugin is licensed under the MIT license. See the `LICENSE`
file for more details.

---
[GitHub project]: https://github.com/wikimedia/composer-merge-plugin
[PSR-2 Coding Standard]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PHP Code Sniffer]: http://pear.php.net/package/PHP_CodeSniffer
