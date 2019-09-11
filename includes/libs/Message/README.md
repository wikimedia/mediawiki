Wikimedia Internationalization Library
======================================

This library provides interfaces and value objects for internationalization (i18n)
of applications in PHP.

It is based on the i18n code used in MediaWiki, and is also intended to be
compatible with [jQuery.i18n], a JavaScript i18n library.

Concepts
--------

Any text string that is needed in an application is a **message**. This might
be something like a button label, a sentence, or a longer text. Each message is
assigned a **message key**, which is used as the identifier in code.

Each message is translated into various languages, each represented by a
**language code**. The message's text (as translated into each language) can
contain **placeholders**, which represents a place in the message where a
**parameter** is to be inserted, and **formatting commands**. It might be plain
text other than these placeholders and formatting commands, or it might be in a
**markup language** such as wikitext or Markdown.

A **formatter** is used to convert the message key and parameters into a text
representation in a particular language and **output format**.

The library itself imposes few restrictions on all of these concepts; this
document contains recommendations to help various implementations operate in
compatible ways.

Usage
-----

<pre lang="php">
use Wikimedia\Message\MessageValue;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\ParamType;

// Constructor interface
$message = new MessageValue( 'message-key', [
    'parameter',
    new MessageValue( 'another-message' ),
    new MessageParam( ParamType::NUM, 12345 ),
] );

// Fluent interface
$message = ( new MessageValue( 'message-key' ) )
    ->params( 'parameter', new MessageValue( 'another-message' ) )
    ->numParams( 12345 );

// Formatting
$messageFormatter = $serviceContainter->get( 'MessageFormatterFactory' )->getTextFormatter( 'de' );
$output = $messageFormatter->format( $message );
</pre>

Class Overview
--------------

### Messages

Messages and their parameters are represented by newable value objects.

**MessageValue** represents an instance of a message, holding the key and any
parameters. It is mutable in that parameters can be added to the object after
creation.

**MessageParam** is an abstract value class representing a parameter to a message.
It has a type (using constants defined in the **ParamType** class) and a value. It
has two implementations:

- **ScalarParam** represents a single-valued parameter, such as a text string, a
  number, or another message.
- **ListParam** represents a list of values, which will be joined together with
  appropriate separators. It has a "list type" (using constants defined in the
  **ListType** class) defining the desired separators.

#### Machine-readable messages

**DataMessageValue** represents a message with additional machine-readable
data. In addition to the key and message parameters, it holds a "code" and
structured data that would be a useful representation of the message in an API
response or the like.

For example, a message for an "integer out of range" error might have one of
three different keys depending on whether the range has a minimum, maximum, or
both. But all should have the same code (representing the concept of "integer
out of range") and should likely have structured data representing the range
directly as `[ 'min' => 1, 'max' => 10 ]` rather than as a flat array of
MessageParam objects.

### Formatters

A formatter for a particular language is obtained from an implementation of
**IMessageFormatterFactory**. No implementation of this interface is provided by
this library. If an environment needs its formatters to vary behavior on things
other than the language code, for example selecting among multiple sources of
messages or markup language used for processing message texts, it should define
a MessageFormatterFactoryFactory of some sort to provide appropriate
IMessageFormatterFactory implementations.

There is no one base interface for all formatters; the intent is that type
hinting will ensure that the formatter being used will produce output in the
expected output format. The defined output formats are:

- **ITextFormatter** produces plain text output.

No implementation of these interfaces are provided by this library.

Formatter implementations are expected to perform the following procedure to
generate the output string:

1. Fetch the message's translation in the formatter's language. Details of this
   fetching are unspecified here.
   - If no translation is found in the formatter's language, it should attempt
     to fall back to appropriate other languages. Details of the fallback are
     unspecified here.
   - If no translation can be found in any fallback language, a string should
	 be returned that indicates at minimum the message key that was unable to
	 be found.
2. Replace placeholders with parameter values.
   - Note that placeholders must not be replaced recursively. That is, if a
     parameter's value contains text that looks like a placeholder, it must not
     be replaced as if it really were a placeholder.
   - Certain types of parameters are not substituted directly at this stage.
     Instead their placeholders must be replaced with an opaque representation
     that will not be misinterpreted during later stages.
     - Parameters of type RAW or PLAINTEXT
     - TEXT parameters with a MessageValue as the value
     - LIST parameters with any late-substituted value as one of their values.
3. Process any formatting commands.
4. Process the source markup language to produce a string in the desired output
   format. This may be a no-op, and may be combined with the previous step if
   the markup language implements compatible formatting commands.
5. Replace any opaque representations from step 2 with the actual values of
   the corresponding parameters.

Guidelines for Interoperability
-------------------------------

Besides allowing for libraries to safely supply their own translations for
every app using them, and apps to easily use libraries' translations instead of
having to retranslate everything, following these guidelines will also help
open source projects use [translatewiki.net] for crowdsourced volunteer
translation into many languages.

### Language codes

[BCP 47] language tags should be used for language codes. If a supplied
language tag is not recognized, at minimum the corresponding tag with all
optional subtags stripped should be tried as a fallback.

All messages must have a translation in English (code "en"). All languages
should fall back to English as a last resort.

The English translations should use `{{PLURAL:...}}` and `{{GENDER:...}}` even
when English doesn't make a grammatical distinction, to signal to translators
that plural/gender support is available.

Language code "qqq" is reserved for documenting messages. Documentation should
describe the context in which the message is used and the values of all
parameters used with the message. Generally this is written in English.
Attempting to obtain a message formatter for "qqq" should return one for "en"
instead.

Language code "qqx" is reserved for debugging. Rather than retrieving
translations from some underlying storage, every key should act as if it were
translated as something `(key-name: $1, $2, $3)` with the number of
placeholders depending on how many parameters are included in the
MessageValue.

### Message keys

Message keys intended for use with external implementations should follow
certain guidelines for interoperability:

- Keys should be restricted to the regular expression `/^[a-z][a-z0-9-]*$/`.
  That is, it should consist of lowercase ASCII letters, numbers, and hyphen
  only, and should begin with a letter.
- Keys should be prefixed to help avoid collisions. For example, a library
  named "ApplePicker" should prefix its message keys with "applepicker-".
- Common values needing translation, such as names of months and weekdays,
  should not be prefixed by each library. Libraries needing these should use
  keys from the [Common Locale Data Repository][CLDR] and document this
  requirement, and environments should provide these messages.

### Message format

Placeholders are represented by `$1`, `$2`, `$3`, and so on. Text like `$100`
is interpreted as a placeholder for parameter 100 if 100 or more parameters
were supplied, as a placeholder for parameter 10 followed by text "0" if
between ten and 99 parameters were supplied, and as a placeholder for parameter
1 followed by text "00" if between one and nine parameters were supplied.

All formatting commands look like `{{NAME:$value1|$value2|$value3|...}}`. Braces
are to be balanced, e.g. `{{NAME:foo|{{bar|baz}}}}` has $value1 as "foo" and
$value2 as "{{bar|baz}}". The name is always case-insensitive.

Anything syntactically resembling a placeholder or formatting command that does
not correspond to an actual paramter or known command should be left unchanged
for processing by the markup language processor.

Libraries providing messages for use by externally-defined formatters should
generally assume no markup language will be applied, and should avoid
constructs used by common markup languages unless they also make sense when
read as plain text.

### Formatting commands

The following formatting commands should be supported.

#### PLURAL

`{{PLURAL:$count|$formA|$formB|...}}` is used to produce plurals.

$count is a number, which may have been formatted with ParamType::NUM.

The number of forms and which count corresponds to which form depend on the
language, for example English uses `{{PLURAL:$1|one|other}}` while Arabic uses
`{{PLURAL:$1|zero|one|two|few|many|other}}`. Details are defined in
[CLDR][CLDR plurals].

It is not possible to "skip" positions while still suppling later ones. If too
few values are supplied, the final form is repeated for subsequent positions.

If there is an explicit plural form to be given for a specific number, it may
be specified with syntax like `{{PLURAL:$1|one egg|$1 eggs|12=a dozen eggs}}`.

#### GENDER

`{{GENDER:$name|$masculine|$feminine|$unspecified}}` is used to handle
grammatical gender, typically when messages refer to user accounts.

This supports three grammatical genders: "male", "female", and a third option
for cases where the gender is unspecified, unknown, or neither male nor female.
It does not attempt to handle animate-inanimate or [T-V] distinctions.

$name is a user account name or other similar identifier. If the name given
does not correspond to any known user account, it should probably use the
$unspecified gender.

If $feminine and/or $unspecified is not specified, the value of $masculine
is normally used in its place.

#### GRAMMAR

`{{GRAMMAR:$form|$term}}` converts a term to an appropriate grammatical form.

If no mapping for $term to $form exists, $term should be returned unchanged.

See [jQuery.i18n § Grammar][jQuery.i18n grammar] for details.

#### BIDI

`{{BIDI:$text}}` applies directional isolation to the wrapped text, to attempt
to avoid errors where directionally-neutral characters are wrongly displayed
when between LTR and RTL content.

This should output U+202A (left-to-right embedding) or U+202B (right-to-left
embedding) before the text, depending on the directionality of the first
strongly-directional character in $text, and U+202C (pop directional
formatting) after, or do something equivalent for the target output format.

### Supplying translations

Code intending its messages to be used by externally-defined formatters should
supply the translations as described by
[jQuery.i18n § Message File Format][jQuery.i18n file format].

In brief, the base directory of the library should contain a directory named
"i18n". This directory should contain JSON files named by code such as
"en.json", "de.json", "qqq.json", each with contents like:

```json
{
    "@metadata": {
        "authors": [
            "Alice",
            "Bob",
            "Carol",
            "David"
        ],
        "last-updated": "2012-09-21"
    },
    "appname-title": "Example Application",
    "appname-sub-title": "An example application",
    "appname-header-introduction": "Introduction",
    "appname-about": "About this application",
    "appname-footer": "Footer text"
}
```

Formatter implementations should be able to consume message data supplied in
this format, either directly via registration of i18n directories to check or
by providing tooling to incorporate it during a build step.

### Machine-readable data

Libraries producing MessageValues as error messages should generally produce
DataMessageValues instead. Codes should be similar to message keys but need
not be prefixed. Data should be restricted to values that will produce valid
output when passed to `json_encode()`.

Libraries producing MessageValues in other contexts should consider whether the
same applies to those contexts.


---
[jQuery.i18n]: https://github.com/wikimedia/jquery.i18n
[BCP 47]: https://tools.ietf.org/rfc/bcp/bcp47.txt
[CLDR]: http://cldr.unicode.org/
[CLDR plurals]: https://www.unicode.org/cldr/charts/latest/supplemental/language_plural_rules.html
[jQuery.i18n grammar]: https://github.com/wikimedia/jquery.i18n#grammar
[jQuery.i18n file format]: https://github.com/wikimedia/jquery.i18n#message-file-format
[translatewiki.net]: https://translatewiki.net/wiki/Translating:New_project
[T-V]: https://en.wikipedia.org/wiki/T%E2%80%93V_distinction
