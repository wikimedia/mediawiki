const LanguageSelector = require( './LanguageSelector.vue' );
const LookupLanguageSelector = require( './LookupLanguageSelector.vue' );
const { getLookupLanguageSelector, getMultiselectLookupLanguageSelector } = require( './factory.js' );

module.exports = {
	LookupLanguageSelector,
	LanguageSelector,
	getLookupLanguageSelector,
	getMultiselectLookupLanguageSelector
};
