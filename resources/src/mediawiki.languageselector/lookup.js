const Vue = require( 'vue' );
const { h } = Vue;
const LanguageSelector = require( './LanguageSelector.vue' );
const useLanguageLookup = require( './useLanguageLookup.js' );

/**
 * @param {Object} config
 * @param {boolean} isMultiple Whether it is a multi-select.
 * @return {Object} Vue app
 */
function getLanguageSelector( config, isMultiple = false ) {
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
			return h( LanguageSelector, {
				isMultiple,
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
	LanguageSelector,
	getLookupLanguageSelector: ( config ) => getLanguageSelector( config, false ),
	getMultiselectLookupLanguageSelector: ( config ) => getLanguageSelector( config, true ),
	useLanguageLookup
};
