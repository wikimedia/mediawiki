# Minify - [minifier.org](https://www.minifier.org)

[![Build status](https://api.travis-ci.org/matthiasmullie/minify.svg?branch=master)](https://travis-ci.org/matthiasmullie/minify)
[![Code coverage](http://img.shields.io/codecov/c/github/matthiasmullie/minify.svg)](https://codecov.io/github/matthiasmullie/minify)
[![Code quality](http://img.shields.io/scrutinizer/g/matthiasmullie/minify.svg)](https://scrutinizer-ci.com/g/matthiasmullie/minify)
[![Latest version](http://img.shields.io/packagist/v/matthiasmullie/minify.svg)](https://packagist.org/packages/matthiasmullie/minify)
[![Downloads total](http://img.shields.io/packagist/dt/matthiasmullie/minify.svg)](https://packagist.org/packages/matthiasmullie/minify)
[![License](http://img.shields.io/packagist/l/matthiasmullie/minify.svg)](https://github.com/matthiasmullie/minify/blob/master/LICENSE)


**[Donate/Support: ![Support](https://www.mullie.eu/public/donate.png)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=73CVYMBETDAP4)**

Removes whitespace, strips comments, combines files (incl. `@import` statements and small assets in CSS files), and optimizes/shortens a few common programming patterns, such as:

**JavaScript**
* `object['property']` -> `object.property`
* `true`, `false` -> `!0`, `!1`
* `while(true)` -> `for(;;)`

**CSS**
* `@import url("http://path")` -> `@import "http://path"`
* `#ff0000`, `#ff00ff` -> `red`, `#f0f`
* `-0px`, `50.00px` -> `0`, `50px`
* `bold` -> `700`
* `p {}` -> removed

And it comes with a huge test suite.


## Usage

### CSS

```php
use MatthiasMullie\Minify;

$sourcePath = '/path/to/source/css/file.css';
$minifier = new Minify\CSS($sourcePath);

// we can even add another file, they'll then be
// joined in 1 output file
$sourcePath2 = '/path/to/second/source/css/file.css';
$minifier->add($sourcePath2);

// or we can just add plain CSS
$css = 'body { color: #000000; }';
$minifier->add($css);

// save minified file to disk
$minifiedPath = '/path/to/minified/css/file.css';
$minifier->minify($minifiedPath);

// or just output the content
echo $minifier->minify();
```

### JS

```php
// just look at the CSS example; it's exactly the same, but with the JS class & JS files :)
```


## Methods

Available methods, for both CSS & JS minifier, are:

### __construct(/* overload paths */)

The object constructor accepts 0, 1 or multiple paths of files, or even complete CSS/JS content, that should be minified.
All CSS/JS passed along, will be combined into 1 minified file.

```php
use MatthiasMullie\Minify;
$minifier = new Minify\JS($path1, $path2);
```

### add($path, /* overload paths */)

This is roughly equivalent to the constructor.

```php
$minifier->add($path3);
$minifier->add($js);
```

### minify($path)

This will minify the files' content, save the result to $path and return the resulting content.
If the $path parameter is omitted, the result will not be written anywhere.

*CAUTION: If you have CSS with relative paths (to imports, images, ...), you should always specify a target path! Then those relative paths will be adjusted in accordance with the new path.*

```php
$minifier->minify('/target/path.js');
```

### gzip($path, $level)

Minifies and optionally saves to a file, just like `minify()`, but it also `gzencode()`s the minified content.

```php
$minifier->gzip('/target/path.js');
```

### setMaxImportSize($size) *(CSS only)*

The CSS minifier will automatically embed referenced files (like images, fonts, ...) into the minified CSS, so they don't have to be fetched over multiple connections.

However, for really large files, it's likely better to load them separately (as it would increase the CSS load time if they were included.)

This method allows the max size of files to import into the minified CSS to be set (in kB). The default size is 5.

```php
$minifier->setMaxImportSize(10);
```

### setImportExtensions($extensions) *(CSS only)*

The CSS minifier will automatically embed referenced files (like images, fonts, ...) into minified CSS, so they don't have to be fetched over multiple connections.

This methods allows the type of files to be specified, along with their data:mime type.

The default embedded file types are gif, png, jpg, jpeg, svg & woff.

```php
$extensions = array(
    'gif' => 'data:image/gif',
    'png' => 'data:image/png',
);

$minifier->setImportExtensions($extensions);
```


## Installation

Simply add a dependency on matthiasmullie/minify to your composer.json file if you use [Composer](https://getcomposer.org/) to manage the dependencies of your project:

```sh
composer require matthiasmullie/minify
```

Although it's recommended to use Composer, you can actually [include these files](https://github.com/matthiasmullie/minify/issues/83) anyway you want.


## Try it

Simply try it out online at <https://www.minifier.org>.


## License

Minify is [MIT](http://opensource.org/licenses/MIT) licensed.


## Challenges

If you're interested in learning some of the harder technical challenges I've encountered building this, you probably want to take a look at [what I wrote about it](http://www.mullie.eu/dont-build-your-own-minifier/) on my blog.
