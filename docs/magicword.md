Magic Words
====================================

Magic words are localizable keywords used in wikitext. They are used for many
small fragments of text, including:

* The names of parser functions e.g. `{{urlencode:...}}`
* The names of variables, e.g. `{{CURRENTDAY}}`
* Double-underscore behavior switches, e.g. `__NOTOC__`
* Image link parameter names

Magic words have a synonym list, with the canonical English word always present,
and a case sensitivity flag. The MagicWord class provides facilities for
matching a magic word by converting it to a regex.

A magic word has a unique ID. Often, the ID is the canonical English synonym in
lowercase.

To add a magic word in an extension, add a file to the **ExtensionMessagesFiles**
attribute in extension.json,
and in that file, set a variable called **$magicWords**. This array is associative
with the language code in the first dimension key and an ID in the second key. The
third level array is numerically indexed: the element with key 0 contains the case
sensitivity flag, with 0 for case-insensitive and 1 for case-sensitive. The
subsequent elements of the array are the synonyms in the relevant language.

To add a magic word in core, add it to $magicWords in MessagesEn.php, following the
comment there.

For example, to add a new parser function in an extension: create a file called
**ExtensionName.i18n.magic.php** with the following contents:

```php
<?php

$magicWords = [];

$magicWords['en'] = [
	// Case insensitive.
	'mag_custom' => [ 0, 'custom' ],
];

$magicWords['es'] = [
	'mag_custom' => [ 0, 'aduanero' ],
];
```

Then in extension.json:

```json
{
	"ExtensionMessagesFiles": {
		"ExtensionNameMagic": "ExtensionName.i18n.magic.php"
	},
	"Hooks": {
		"ParserFirstCallInit": "MyExtensionHooks::onParserFirstCallInit"
	}
}
```

It is important that the key "ExtensionNameMagic" is unique. It must not be used
by another extension.

And in the class file:

```php
<?php

class MyExtensionHooks {
	public static function onParserFirstCallInit( $parser ) {
		$parser->setFunctionHook( 'mag_custom', [ self::class, 'expandCustom' ] );
		return true;
	}

	public static function expandCustom( $parser, $var1, $var2 ) {
		return "custom: var1 is $var1, var2 is $var2";
	}
}
```

- Online documentation (contains more informations):
- Magic words: <https://www.mediawiki.org/wiki/Manual:Magic_words>
- Variables: <https://www.mediawiki.org/wiki/Manual:Variable>
- Parser functions: <https://www.mediawiki.org/wiki/Manual:Parser_functions>
