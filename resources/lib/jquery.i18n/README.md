jQuery.i18n
===========

[![npm][npm]][npm-url]

> NOTE: For jquery independent version of this library, see https://github.com/wikimedia/banana-i18n

jQuery.i18n is a jQuery based Javascript internationalization library. It helps you to internationalize your web applications easily.

This is a project by Wikimedia foundation's [Language Engineering team](https://www.mediawiki.org/wiki/Wikimedia_Language_engineering) and used in some of the Wikimedia Foundation projects like Universal Language Selector.

The jquery.i18n library uses a json based localization file format, "banana", which is used as the localization file format for  MediaWiki and other projects.


Features
========
* Simple file format - JSON. Easily readable for humans and machines.
* Author and metadata information is not lost anywhere. There are other file formats using comments to store this.
* Uses MediaWiki convention for placeholders. Easily readable and proven convention. Example: ```There are $1 cars```
* Supports plural conversion without using extra messages for all plural forms. Plural rule handling is done using CLDR. Covers a wide range of languages
* Supports gender. By passing the gender value, you get correct sentences according to gender.
* Supports grammar forms. jquery.i18n has a basic but extensible grammar conversion support
* Fallback chains for all languages.
* Data api- the message key. Example: ```<li data-i18n="message-key"></li>```.
* Dynamic change of interface language without refreshing a webpage.
* Nestable grammar, plural, gender support. These constructs can be nested to any arbitrary level for supporting sophisticated message localization
* Message documentation through special language code ```qqq```
* Extensible message parser to add or customize magic words in the messages. Example: ```{sitename}``` or ```[[link]]```


Quick start
-----------

```bash
git clone https://github.com/wikimedia/jquery.i18n.git
cd jquery.i18n
git submodule update --init
```

Testing
-------

```shell
npm install
```

To run tests locally, run `npm test`, and this will run the tests.

Message File Format
===================

The message files are json formatted. As a convention, you can have a folder named i18n inside your source code. For each language or locale, have a file named like languagecode.json.

Example:
```
App
	|--src
	|--doc
	|--i18n
		|--ar.json
		|--de.json
		|--en.json
		|--he.json
		|--hi.json
		|--fr.json
		|--qqq.json
```

A simple en.json file example is given below

```json
{
	"@metadata": {
		"authors": [
			"Alice",
			"David",
			"Santhosh"
		],
		"last-updated": "2012-09-21",
		"locale": "en",
		"message-documentation": "qqq",
		"AnotherMetadata": "AnotherMedatadataValue"
	},
	"appname-title": "Example Application",
	"appname-sub-title": "An example application with jquery.i18n",
	"appname-header-introduction": "Introduction",
	"appname-about": "About this application",
	"appname-footer": "Footer text"
}
```

The json file should be a valid json. The ```@metadata``` holds all kind of data that are not messages. You can store author information, copyright, updated date or anything there.

Messages are key-value pairs. It is a good convention to prefix your appname to message keys to make the messages unique. It acts as the namespace for the message keys. It is also a good convention to have the message keys with ```-``` separated words, all in lower case.

If you are curious to see some real jquery.i18n message file from other projects:

- message files of MediaWiki https://github.com/wikimedia/mediawiki-core/tree/master/languages/i18n
- message files from jquery.uls project https://github.com/wikimedia/jquery.uls/blob/master/i18n

Single message file for all languages
-------------------------------------
There are some alternate message file formats supported for different use cases. If your application is not big, and want all the translation in a single file, you can have it as shown in the below example:

```json
{
	"@metadata": {
		"authors": [
			"Alice",
			"David",
			"Santhosh"
		],
		"last-updated": "2012-09-21",
		"locale": "en",
		"message-documentation": "qqq",
		"AnotherMetadata": "AnotherMedatadataValue"
	},
	"en": {
		"appname-title": "Example Application",
		"appname-sub-title": "An example application with jquery.i18n",
		"appname-header-introduction": "Introduction",
		"appname-about": "About this application",
		"appname-footer": "Footer text"
		},
	"ml": {
		"appname-title": "അപ്ലിക്കേഷന്‍ ഉദാഹരണം",
		"appname-sub-title": "jquery.i18n ഉപയോഗിച്ചുള്ള അപ്ലിക്കേഷന്‍ ഉദാഹരണം",
		"appname-header-introduction": "ആമുഖം",
		"appname-about": "ഈ അപ്ലിക്കേഷനെപ്പറ്റി",
		"appname-footer": "അടിക്കുറിപ്പു്"
	}
}
```

Here the json file contains language code as key-value and messagekey-message pairs as the value for all language pairs. You can choose this format or per-language file formats depending on your use case. Per-language files are more convenient for collaboration, version controlling, scalability, etc.

In this approach, it is also possible to give a file name as the value of language code.

```json
{
	"@metadata": {
		"authors": [
			"Alice",
			"David",
			"Santhosh"
		],
		"last-updated": "2012-09-21",
		"locale": "en",
		"message-documentation": "qqq",
		"AnotherMetadata": "AnotherMedatadataValue"
	},
	"en": {
		"appname-title": "Example Application",
		"appname-sub-title": "An example application with jquery.i18n",
		"appname-header-introduction": "Introduction",
		"appname-about": "About this application",
		"appname-footer": "Footer text"
		},
	"ml": "path/to/ml.json"
}
```

Translation
===========
To translate the jquery.i18n application, depending on the expertise of the translator, there are multiple ways.

* Editing the json files directly - Suitable for translators with technical background. Also suitable if your application is small and you want to work with only a small number of languages
* Providing a translation interface along with your application: Suitable for proprietary or private applications with significant amount of translators
* Using open source translation platforms like translatewiki.net. The MediaWiki and jquery.uls from previous examples use translatewiki.net for crowdsourced message translation. Translatewiki.net can update your code repo at regular intervals with updated translations. Highly recommended if your application is opensource and want it to be localized to as many as languages possible with maximum number of translators.

Usage
=====

## Switching locale

While initializing the `jquery.i18n`, the locale for the page can be given using the `locale` option. For example

```javascript
$.i18n( {
    locale: 'he' // Locale is Hebrew
} );
```

In case locale option is not given, `jquery.i18n` plugin will use the language attribute given for the html tag. For example

```html
<html lang="he" dir="rtl">
```

In this case, the locale will be he(Hebrew). If that `lang` attribute is also missing, it will try to use the locale specified by the browser.

It is possible to switch to another locale after plugin is initialized. See below example:

```javascript
$.i18n({
    locale: 'he' // Locale is Hebrew
});
$.i18n( 'message-hello' ); // This will give the Hebrew translation of message key `message-hello`.
$.i18n().locale = 'ml'; // Now onwards locale is 'Malayalam'
$.i18n( 'message-hello' ); // This will give the Malayalam translation of message key `message-hello`.
```

## Message Loading

JSON formatted messages can be loaded to the plugin using multiple ways.

### Dynamic loading using `load` method.

Following example shows loading messages for two locales- localex, and localey. Here localex and localey are just examples. They should be valid IS0 639 language codes(eg: en, ml, hi, fr, ta etc)

```javascript
$.i18n().load( {
	'localex' : {
		'message-key1' : 'message1' // Message for localex.
	},
	'localey' : {
		'message-key1' : 'message1'
	}
} );
```

If we want to load the messages for a specific locale, it can be done like this:

```javascript
$.i18n().load({
    'message-hello': 'Hello World',
    'message-welcome': 'Welcome'
}, 'en');
```

Note the second argument for the `load` method. It should be a valid language code.

It is also possible to refer messages from an external URL. See below example

```javascript
$.i18n().load( {
	en: {
		'message-hello': 'Hello World',
		'message-welcome': 'Welcome'
	},
	hi: 'i18n/messages-hi.json', // Messages for Hindi
	de: 'i18n/messages-de.json'
} );
```

Messages for a locale can be also loaded in parts. Example

```javascript
$.i18n().load( {
	en: {
		'message-hello': 'Hello World',
		'message-welcome': 'Welcome'
	}
} );

$.i18n().load( {
    	// This does not remove the previous messages.
	en: {
		'message-header' : 'Header',
		'message-footer' : 'Footer',
		// This will overwrite message-welcome message
		'message-welcome' : 'Welcome back'
	}
} );
```

Since it is desirable to render interface messages instantly and not after a delay of loading the message files from a server, make sure that the messages are present at client side before using jQuery.i18n.

The library should expose an API to load an object containing key-value pair of messages. Example: ```$.i18n.load(data)```. This will return a ```jQuery.Promise```.

jquery.i18n plugin
=========================

The jQuery plugin defines ```$.i18n()``` and ```$.fn.i18n()```

```javascript
$.i18n( 'message-key-sample1' );
$.i18n( 'message-key-sample1' );
$.i18n( 'Found $1 {{plural:$1|result|results}}', 10 ); // Message key itself is message text
$.i18n( 'Showing $1 out of $2 {{plural:$2|result|results}}', 5,100 );
$.i18n( 'User X updated {{gender|his|her}} profile', 'male' );

$( '#foo' ).i18n(); // to translate the element matching jquery selector based on data-i18n key
```

Data API
--------

It is possible to display localized messages without any custom JavaScript. For the HTML tags, add an attribute data-i18n with value as the message key. Example:
```html
<li data-i18n="message-key"></li>.
```

It is also possible to have the above li node with fallback text already in place.
```html
<li data-i18n="message-key">Fallback text</li>
```

The framework will place the localized message corresponding to message-key as the text value of the node. Similar to $('selector').i18n( ... ).
This will not work for dynamically created elements.

Note that if data-i18n contains html markup, that html will not be used as the element content, instead, the text version will be used. But if the message key is prefixed with `[html]`, the element's html will be changed. For example ```<li data-i18n="[html]message-key">Fallback html</li>```, in this if the message-key has a value containing HTML markup, the `<li>` tags html will be replaced by that html.


If you want to change the html of the element, you can also use: ```$(selector).html($.i18n(messagekey))```

Examples
========

See https://thottingal.in/projects/js/jquery.i18n/demo/

Message format
==============

## Placeholders

Messages take parameters. They are represented by $1, $2, $3, … in the message texts, and replaced at run time. Typical parameter values are numbers (Example: "Delete 3 versions?"), or user names (Example: "Page last edited by $1"), page names, links, and so on, or sometimes other messages.

```javascript
var message = "Welcome, $1";
$.i18n(message, 'Alice'); // This gives "Welcome, Alice"
```


## Plurals

To make the syntax of sentence correct, plural forms are required. jquery.i18n support plural forms in the message using the syntax `{{PLURAL:$1|pluralform1|pluralform2|...}}`

For example:

```javascript
var message = "Found $1 {{PLURAL:$1|result|results}}";
$.i18n(message, 1); // This gives "Found 1 result"
$.i18n(message, 4); // This gives "Found 4 results"
```
Note that {{PLURAL:...}} is not case sensitive. It can be {{plural:...}} too.

In case of English, there are only 2 plural forms, but many languages use more than 2 plural forms. All the plural forms can be given in the above syntax, separated by pipe(|). The number of plural forms for each language is defined in [CLDR](https://www.unicode.org/cldr/charts/latest/supplemental/language_plural_rules.html). You need to provide all those plural forms for a language. Please note that many languages will require the inclusion of `CLDRPluralRuleParser.js` ([from here](https://github.com/santhoshtr/CLDRPluralRuleParser/tree/8baf9aedc428924fe6ee508b3d952cb5564efb3a/src)) as well as this project's own files to work properly.

For example, English has 2 plural forms and the message format will look like `{{PLURAL:$1|one|other}}`. for Arabic there are 6 plural forms and format will look like `{{PLURAL:$1|zero|one|two|few|many|other}}`.

You cannot skip a plural form from the middle or beginning. However, you can skip from end. For example, in Arabic, if the message is like
`{{PLURAL:$1|A|B}}`, for 0, A will be used, for numbers that fall under one, two, few, many, other categories B will be used.

If there is an explicit plural form to be given for a specific number, it is possible with the following syntax

```
var message = 'Box has {{PLURAL:$1|one egg|$1 eggs|12=a dozen eggs}}.';
$.i18n(message, 4 ); // Gives "Box has 4 eggs."
$.i18n(message, 12 ); // Gives "Box has a dozen eggs."
```

## Gender
Similar to plural, depending on gender of placeholders, mostly user names, the syntax changes dynamically. An example in English is "Alice changed her profile picture" and "Bob changed his profile picture". To support this {{GENDER...}} syntax can be used as shown in example

```javascript
var message = "$1 changed {{GENDER:$2|his|her}} profile picture";
$.i18n(message, 'Alice', 'female' ); // This gives "Alice changed her profile picture"
$.i18n(message, 'Bob', 'male' ); // This gives "Bob changed his profile picture"
```

Note that {{GENDER:...}} is not case sensitive. It can be {{gender:...}} too.

## Grammar


```javascript
$.i18n( { locale: 'fi' } );

var message = "{{grammar:genitive|$1}}";

$.i18n(message, 'talo' ); // This gives "talon"

$.i18n().locale = 'hy'; // Switch to locale Armenian
$.i18n(message, 'Մաունա'); // This gives "Մաունայի"
```

## Directionality-safe isolation

To avoid BIDI corruption that looks like "(Foo_(Bar", which happens when a string is inserted into a context with the reverse directionality, you can use `{{bidi:…}}`. Directionality-neutral characters at the edge of the string can get wrongly interpreted by the BIDI algorithm. This would let you embed your substituted string into a new BIDI context, //e.g.//:

   "`Shalom, {{bidi:$1}}, hi!`"

The embedded context's directionality is determined by looking at the argument for `$1`, and then explicitly inserted into the Unicode text, ensuring correct rendering (because then the bidi algorithm "knows" the argument text is a separate context).


Fallback
========

The plugin takes an option 'fallback' with the default value 'en'. The library reuses the fallback data available in MediaWiki for calculating the language fallbacks. Fallbacks are used when a message key is not found in a locale. Example fallbacks: sa->hi->en or tt->tt-cyrl->ru.

See jquery.i18n.fallbacks.js in the source.

Magic word support
===================
* For plural, gender and grammar support, MediaWiki template-like syntax - {{...}} will be used.
* There will be a default implementation for all these in $.i18n.language['default']
* The plural, gender and grammar methods in ```$.i18n.language[ 'default' ]``` can be overridden or extended in ```$.i18n.language['languageCode']```.
* Language-specific rules about Gender and Grammar can be written in languages/langXYZ.js files
* Plural forms will be dynamically calculated using the CLDR plural parser.

Extending the parser
--------------------
Following example illustrates extending the parser to support more magic words

```javascript
$.extend( $.i18n.parser.emitter, {
	// Handle SITENAME keywords
	sitename: function () {
		return 'Wikipedia';
	},
	// Handle LINK keywords
	link: function ( nodes ) {
		return '<a href="' + nodes[1] + '">' + nodes[0] + '</a>';
	}
} );
```

This will parse the message
```javascript
$.i18n( '{{link:{{SITENAME}}|https://en.wikipedia.org}}' );
```

to

```html
<a href="https://en.wikipedia.org">Wikipedia</a>
```

Message documentation
=====================

The message keys and messages won't give a enough context about the message being translated to the translator. Whenever a developer adds a new message, it is a usual practice to document the message to a file named qqq.json
with same message key.

Example qqq.json:
```json
{
	"@metadata": {
		"authors": [
			"Developer Name"
		]
	},
	"appname-title": "Application name. Transliteration is recommended",
	"appname-sub-title": "Brief explanation of the application",
	"appname-header-introduction": "Text for the introduction header",
	"appname-about": "About this application text",
	"appname-footer": "Footer text"
}

```

In MediaWiki and its hundreds of extensions, message documentation is a strictly followed practice. There is a grunt task to check whether all messages are documented or not. See https://www.npmjs.org/package/grunt-banana-checker

[npm]: https://img.shields.io/npm/v/@wikimedia/jquery.i18n.svg
[npm-url]: https://npmjs.com/package/@wikimedia/jquery.i18n
