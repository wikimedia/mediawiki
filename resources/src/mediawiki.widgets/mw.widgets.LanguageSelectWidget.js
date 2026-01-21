/*!
 * MediaWiki Widgets - LanguageSelectWidget replacement.
 *
 * Replaces native HTML select elements with Codex language selector.
 */
( function () {

	function replaceWithVue( nativeSelect ) {
		const { getLookupLanguageSelector } = require( 'mediawiki.languageselector' );
		// Get configuration from data attributes
		const languagesAttr = nativeSelect.getAttribute( 'data-mw-languages' );
		const languages = languagesAttr && languagesAttr !== 'null' ? JSON.parse( languagesAttr ) : null;
		// Get the selected value from the select element (or from the selected option)
		const selectedOption = nativeSelect.querySelector( 'option[selected]' );
		const selectedValue = selectedOption ? selectedOption.value : ( nativeSelect.value || null );

		// Hide the native select (but keep it for form submission)
		nativeSelect.style.display = 'none';

		// Create container for Vue component and insert it after the select element
		const vueContainer = document.createElement( 'div' );
		vueContainer.classList.add( 'mw-widgets-languageSelectWidget-vue-container' );
		nativeSelect.parentNode.insertBefore( vueContainer, nativeSelect.nextSibling );

		// Create Vue app using the factory function
		const vueApp = getLookupLanguageSelector( {
			selectableLanguages: languages,
			selectedLanguage: selectedValue,
			// FIXME: the language labels will changed based on
			// https://phabricator.wikimedia.org/T414468
			menuItemSlot: ( { languageCode, languageName } ) => languageCode + ' - ' + languageName,
			onLanguageChange: ( newValue ) => {
				// Update native select for form submission
				nativeSelect.value = newValue || '';
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
