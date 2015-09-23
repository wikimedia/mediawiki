[![Latest Stable Version](https://poser.pugx.org/wikimedia/utfnormal/v/stable.svg)](https://packagist.org/packages/wikimedia/utfnormal) [![License](https://poser.pugx.org/wikimedia/utfnormal/license.svg)](https://packagist.org/packages/wikimedia/utfnormal)

utfnormal
=========

utfnormal is a library that contains Unicode normalization routines, including
both pure PHP implementations and automatic use of the 'intl' PHP extension when
 present.

The main function to care about is UtfNormal\Validator::cleanUp(). This will
strip illegal UTF-8 sequences and characters that are illegal in XML, and
if necessary convert to normalization form C.

If you know the string is already valid UTF-8, you can directly call
UtfNormal\Validator::toNFC(), toNFK(), or toNFKC(); this will convert a given
UTF-8 string to Normalization Form C, K, or KC if it's not already such.
The function assumes that the input string is already valid UTF-8; if there
are corrupt characters this may produce erroneous results.

Performance is kind of stinky in absolute terms, though it should be speedy
on pure ASCII text. ;) On text that can be determined quickly to already be
in NFC it's not too awful but it can quickly get uncomfortably slow,
particularly for Korean text (the hangul decomposition/composition code is
extra slow).

Bugs should be filed in [Wikimedia's Phabricator] under the "utfnormal" project.


Regenerating data tables
------------------------
UtfNormalData.inc and UtfNormalDataK.inc are generated from the Unicode
Character Database by the script "generate.php". Run "composer generate"
to rebuild the tables. To fetch updated unicode data from the internet,
run "composer generate -- --fetch".


Testing
-------

Running "composer test" will run a syntax checker, PHPUnit conformance tests,
and run some benchmarks using sample texts from Wikipedia. Take all benchmark
numbers with large grains of salt.


PHP module extension
--------------------

If the 'intl' PHP extension is present, ICU library functions are used which
are *MUCH* faster than doing this work in pure PHP code.

It is strongly recommended to enable this module if possible:
http://php.net/manual/en/intro.intl.php

Older versions of this library supported a one-off custom PHP extension,
which has been dropped. If you were using this, please migrate to the
intl extension.


History
-------
This library was first introduced in [MediaWiki 1.3][] ([r4965]). It was
split out of the MediaWiki codebase and published as an independent library
during the [MediaWiki 1.25][] development cycle.

---
[Wikimedia's Phabricator]: https://phabricator.wikimedia.org/maniphest/task/create/?projects=utfnormal
[MediaWiki 1.3]: https://www.mediawiki.org/wiki/MediaWiki_1.3
[r4965]: https://www.mediawiki.org/wiki/Special:Code/MediaWiki/4965
[MediaWiki 1.25]: https://www.mediawiki.org/wiki/MediaWiki_1.25
