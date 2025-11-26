/*!
 * JavaScript module used on Special:PageLanguage
 */
( function () {
	$( () => {
		const $languageSelector = $( '#mw-pl-languageselector' );
		const pageLanguageOptions = OO.ui.infuse( $( '#mw-pl-options' ) );
		pageLanguageOptions.setValue( '1' );

		if ( $languageSelector ) {
			mw.loader.using( 'mediawiki.languageselector' ).then( () => {
				let isValidSelection = true;
				const languageSelectorWidget = OO.ui.infuse( $languageSelector );

				const initialLanguageCode = languageSelectorWidget.getValue();
				replaceOOUILanguageSelector( $languageSelector, initialLanguageCode, ( languageCode ) => {
					if ( languageCode ) {
						isValidSelection = true;
						languageSelectorWidget.setValue( languageCode );
						// Select the 'Language select' option if the user is trying to select a language
						pageLanguageOptions.setValue( '2' );
					} else {
						isValidSelection = false;
					}
				} );

				$( '#mw-pagelanguage-form' ).on( 'submit', ( e ) => {
					if ( !isValidSelection ) {
						e.preventDefault();
						mw.notify( mw.msg( 'pagelang-invalid-selection' ), { type: 'error' } );
						return false;
					}
				} );
			} );
		}
	} );

	function replaceOOUILanguageSelector( $languageSelector, selectedLanguage, onLanguageChange ) {
		$languageSelector.hide();

		const { getLookupLanguageSelector } = require( 'mediawiki.languageselector' );
		const languageSelectorApp = getLookupLanguageSelector(
			{
				selectedLanguage,
				menuConfig: { visibleItemLimit: 8 },
				menuItemSlot: ( { languageCode, languageName } ) => [
					languageName + ' (' + languageCode + ')'
				],
				onLanguageChange
			}
		);

		// Mount the Vue app after the hidden OOUI selector
		const container = document.createElement( 'div' );
		$languageSelector.after( container );
		languageSelectorApp.mount( container );
	}
}() );
