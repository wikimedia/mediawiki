const LookupLanguageSelector = require( './LookupLanguageSelector.vue' );
const { getLookupLanguageSelector } = require( './lookup-factory.js' );
const MultiselectLookupLanguageSelector = require( './MultiselectLookupLanguageSelector.vue' );
const { getMultiselectLookupLanguageSelector } = require( './multiselect-factory.js' );
const useLanguageLookup = require( './useLanguageLookup.js' );

module.exports = {
	LookupLanguageSelector,
	getLookupLanguageSelector,
	MultiselectLookupLanguageSelector,
	getMultiselectLookupLanguageSelector,
	useLanguageLookup
};
