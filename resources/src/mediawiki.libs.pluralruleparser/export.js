/**
 * Exposes the {@link https://github.com/santhoshtr/CLDRPluralRuleParser cldrpluralparser.js} library, a parser engine for CLDR plural rules.
 *
 * @exports mediawiki.libs.pluralruleparser
 */
module.exports = window.pluralRuleParser;

// Back-compat: Also expose via mw.lib
mw.libs.pluralRuleParser = window.pluralRuleParser;
