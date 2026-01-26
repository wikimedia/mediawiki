const Vue = require( 'vue' );
const { h } = Vue;
const LookupLanguageSelector = require( './LookupLanguageSelector.vue' );
const MultiselectLookupLanguageSelector = require( './MultiselectLookupLanguageSelector.vue' );

/**
 * Create a Vue application for the LookupLanguageSelector component.
 *
 * @typedef {Object} LookupLanguageSelectorConfig
 * @property {Object|null} [selectableLanguages=null] An object mapping language codes to labels for selectable languages.
 * @property {string|null} [selectedLanguage=null] The language code to select initially.
 * @property {Object} [menuConfig={}] Configuration for the Codex Lookup menu. See https://doc.wikimedia.org/codex/latest/components/types-and-constants.html#menuconfig
 * @property {string|null} [apiUrl=null] The API URL to use for language search. Defaults to the current wiki's API.
 * @property {string|null} [placeholder=null] Placeholder text for the input field.
 * @property {boolean|false} [disabled=false] Whether the lookup is disabled.
 * @property {boolean|false} [required=false] Whether the lookup is required.
 * @property {Function|null} [menuItemSlot=null] Slot function for the menu item. Receives the slot props object `{ menuItem, languageCode, languageName }` as an argument and should return a VNodeChild (see https://vuejs.org/api/options-rendering#render).
 * @property {Function|null} [onLanguageChange=null] Callback function when language is selected. Received the selected language code as an argument.
 *
 * @param {LookupLanguageSelectorConfig} config The configuration object for the selector.
 * @return {Object} The Vue application instance.
 */
function getLookupLanguageSelector( config ) {
	const {
		selectableLanguages = null,
		selectedLanguage = null,
		menuConfig = {},
		apiUrl = null,
		placeholder = null,
		disabled = false,
		required = false,
		menuItemSlot = null,
		onLanguageChange = null
	} = config;

	return Vue.createMwApp( {
		data() {
			return {
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
			return h( LookupLanguageSelector, {
				searchApiUrl: this.apiUrl,
				selectableLanguages: this.selectableLanguages,
				selected: this.selectedLanguage,
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

/**
 * Create a Vue application for the MultiselectLookupLanguageSelector component.
 *
 * @typedef {Object} MultiselectLookupLanguageSelectorConfig
 * @property {Array|null} [selectableLanguages=null] List of language codes selectable in the component.
 * @property {Array|null} [selectedLanguage=null] The language code(s) to select initially.
 * @property {Object} [menuConfig={}] Configuration for the Codex Lookup menu.
 * @property {string|null} [apiUrl=null] The API URL to use for language search. Defaults to the current wiki's API.
 * @property {string|null} [placeholder=null] Placeholder text for the input field.
 * @property {boolean|false} [disabled=false] Whether the lookup is disabled.
 * @property {boolean|false} [required=false] Whether the lookup is required.
 * @property {Function|null} [menuItemSlot=null] Slot function for the menu item.
 * @property {Function|null} [onLanguageChange=null] Callback function when language is selected/changed.
 *
 * @param {MultiselectLookupLanguageSelectorConfig} config The configuration object for the selector.
 * @return {Object} The Vue application instance
 */
function getMultiselectLookupLanguageSelector( config ) {
	const {
		selectableLanguages = null,
		selectedLanguage = null,
		menuConfig = {},
		apiUrl = null,
		placeholder = null,
		disabled = false,
		required = false,
		menuItemSlot = null,
		onLanguageChange = null
	} = config;

	return Vue.createMwApp( {
		data() {
			return {
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
			return h( MultiselectLookupLanguageSelector, {
				searchApiUrl: this.apiUrl,
				selected: this.selectedLanguage,
				selectableLanguages: this.selectableLanguages,
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
	getLookupLanguageSelector,
	getMultiselectLookupLanguageSelector
};
