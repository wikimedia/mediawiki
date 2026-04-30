const Vue = require( 'vue' );
const { h } = Vue;
const LookupLanguageSelector = require( './LookupLanguageSelector.vue' );
const MultiselectLookupLanguageSelector = require( './MultiselectLookupLanguageSelector.vue' );
const useLanguageLookup = require( './useLanguageLookup.js' );

/**
 * @param {Object} config
 * @param {Object} Component The wrapper component to render.
 * @return {Object} Vue app
 */
function getLanguageSelector( config, Component ) {
	const {
		selectableLanguages = null,
		selectedLanguage = null,
		menuConfig = {},
		apiUrl = null,
		placeholder = null,
		disabled = false,
		required = false,
		menuItemSlot = null,
		onLanguageChange = null,
		inputId = ''
	} = config;

	return Vue.createMwApp( {
		data() {
			return {
				inputId,
				apiUrl: apiUrl || mw.util.wikiScript( 'api' ),
				selectedLanguage,
				selectableLanguages,
				menuConfig,
				placeholder,
				disabled,
				required
			};
		},
		render() {
			return h( Component, {
				searchApiUrl: this.apiUrl,
				selectableLanguages: this.selectableLanguages,
				selected: this.selectedLanguage,
				inputId: this.inputId,
				placeholder: this.placeholder,
				disabled: this.disabled,
				required: this.required,
				'onUpdate:selected': ( newValue ) => {
					this.selectedLanguage = newValue;
					if ( onLanguageChange ) {
						onLanguageChange( newValue );
					}
				},
				menuConfig: this.menuConfig
			}, {
				'menu-item': menuItemSlot
			} );
		}
	} );
}

module.exports = {
	LookupLanguageSelector,
	MultiselectLookupLanguageSelector,
	getLookupLanguageSelector: ( config ) => getLanguageSelector( config, LookupLanguageSelector ),
	getMultiselectLookupLanguageSelector: ( config ) => getLanguageSelector( config, MultiselectLookupLanguageSelector ),
	useLanguageLookup
};
