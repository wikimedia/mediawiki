# CLDR Plural Rule Evaluator

Find out the plural form for a given number in a language

[![NPM version](https://badge.fury.io/js/cldrpluralruleparser.svg)](https://www.npmjs.org/package/cldrpluralruleparser)
[![Build Status](https://github.com/santhoshtr/CLDRPluralRuleParser/actions/workflows/node.yml/badge.svg)](https://github.com/santhoshtr/CLDRPluralRuleParser/actions/workflows/node.yml)

## Quick start

```bash
git clone https://github.com/santhoshtr/CLDRPluralRuleParser.git
npm install
```

## Documentation

Unlike English, for many languages, the plural forms are just not 2 forms.
If you look at the [CLDR plural rules table](http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#pl)
you can easily understand this. The rules are defined in a particular syntax
(an eg: for Russian, the plural few is applied when the rule
`n mod 10 in 2..4 and n mod 100 not in 12..14;` is passed).

This tool is a demonstration of a [javascript parser](./src/CLDRPluralRuleParser.js)
for the plural rules in that syntax.

For a given number in a language, this tool tells which plural form it belongs.
The plural rules are taken from the CLDR data file.

## Test

```bash
npm test
```

## Node module

This is also available as a node module. You can install it using:

```bash
npm install cldrpluralruleparser
```

Once installed it provides a command line utility named `cldrpluralruleparser` too.

```bash
$ cldrpluralruleparser 'n is 1' 0
false
```

## CLDR Version compatibility

This parser is expected to handle latest version of CLDR spec for plurals and data from latest version.
This is tested by running all rules of plurals.json with the parser.

**Maintainers Note**

Download latest plural.json from CLDR Json releases from https://github.com/unicode-org/cldr-json
and replace demo/plurals.json and run `npm run test`. All tests should pass. If CLDR updates the spec,
and plurals.json has unsupported rules, tests will fail and we need to update the parser.


## Reference

* https://cldr.unicode.org/index/cldr-spec/plural-rules
