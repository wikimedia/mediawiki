/*!
 * JavaScript module used on Special:PageLanguage
 */
( function () {
	$( () => {
		// Select the 'Language select' option if user is trying to select language
		if ( $( '#mw-pl-languageselector' ).length ) {
			OO.ui.infuse( $( '#mw-pl-languageselector' ) ).on( 'change', () => {
				OO.ui.infuse( $( '#mw-pl-options' ) ).setValue( '2' );
			} );
		}
	} );
}() );
