/*!
 * JavaScript module used on Special:PageLanguage
 */
( function () {
	$( () => {
		const languageSelector = document.getElementById( 'mw-pl-languageselector' );
		if ( !languageSelector ) {
			return;
		}

		const pageLanguageOptions = OO.ui.infuse( $( '#mw-pl-options' ) );
		pageLanguageOptions.setValue( '1' );

		// Wait for the widget to replace the native select with Vue component
		mw.loader.using( 'mediawiki.widgets.LanguageSelectWidget' ).then( () => {
			languageSelector.addEventListener( 'change', () => {
				const languageCode = languageSelector.value;
				if ( languageCode ) {
					// Select the 'Language select' option if the user is trying to select a language
					pageLanguageOptions.setValue( '2' );
				}
			} );

			$( '#mw-pagelanguage-form' ).on( 'submit', ( e ) => {
				const selectedOption = pageLanguageOptions.getValue();
				const languageCode = languageSelector.value;
				if ( selectedOption === '2' && !languageCode ) {
					e.preventDefault();
					mw.notify( mw.msg( 'pagelang-invalid-selection' ), { type: 'error' } );
					return false;
				}
			} );
		} );
	} );
}() );
