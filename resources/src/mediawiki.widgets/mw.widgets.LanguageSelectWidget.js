/*!
 * MediaWiki Widgets - LanguageSelectWidget replacement.
 *
 * Replaces native HTML select elements with Codex language selector.
 */
( function () {

	// Replace a native HTML select element with Vue component.
	function replaceWithVue( nativeSelect ) {
		const isMultiple = nativeSelect.hasAttribute( 'multiple' );
		const {
			getLookupLanguageSelector,
			getMultiselectLookupLanguageSelector
		} = require( 'mediawiki.languageselector' );

		// Get configuration from data attributes
		const languagesAttr = nativeSelect.getAttribute( 'data-mw-languages' );
		const languages = languagesAttr && languagesAttr !== 'null' ? JSON.parse( languagesAttr ) : null;

		let selectedLanguage;

		const optionLabels = {};
		Array.from( nativeSelect.options ).forEach( ( option ) => {
			optionLabels[ option.value ] = option.textContent;
		} );

		// Hide the native select (but keep it for form submission)
		nativeSelect.style.display = 'none';

		if ( isMultiple ) {
			selectedLanguage = Array.from( nativeSelect.selectedOptions ).map( ( option ) => option.value );
		} else {
			selectedLanguage = nativeSelect.value;
		}

		// Create container for Vue component and insert it after the select element
		const vueContainer = document.createElement( 'div' );
		vueContainer.classList.add( 'mw-widgets-languageSelectWidget-vue-container' );
		nativeSelect.parentNode.insertBefore( vueContainer, nativeSelect.nextSibling );

		// Create Vue app using the factory function
		const factory = isMultiple ? getMultiselectLookupLanguageSelector : getLookupLanguageSelector;
		const vueApp = factory( {
			selectableLanguages: languages,
			selectedLanguage: selectedLanguage,
			menuItemSlot: ( { languageCode } ) => optionLabels[ languageCode ],
			onLanguageChange: ( newValue ) => {
				if ( isMultiple ) {
					// Iterate over the options and set the 'selected' property for multiple select
					Array.from( nativeSelect.options ).forEach( ( option ) => {
						option.selected = newValue.includes( option.value );
					} );
				} else {
					// the value is single value (en)
					nativeSelect.value = newValue || '';
				}
				// Trigger change event on native select for compatibility
				nativeSelect.dispatchEvent( new Event( 'change', { bubbles: true } ) );
			}
		} );

		// Mount Vue app to container
		vueApp.mount( vueContainer );

		return vueApp;
	}

	// Load Vue module and process all native select elements.
	function loadVueInNativeSelects() {
		// Find all select elements marked for replacement
		const nativeSelects = document.querySelectorAll(
			'.mw-widgets-languageSelectWidget-select'
		);

		// Process each select and return array of Vue app instances
		return Array.from( nativeSelects ).map( replaceWithVue );
	}

	// Replace native HTML select elements with Codex language selector.
	function replaceLanguageSelects() {
		// Load the language selector module once, then process all selects
		mw.loader.using( 'mediawiki.languageselector' ).then( () => {
			loadVueInNativeSelects();
		} );
	}

	// Replace on page load
	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', replaceLanguageSelects );
	} else {
		replaceLanguageSelects();
	}
}() );
