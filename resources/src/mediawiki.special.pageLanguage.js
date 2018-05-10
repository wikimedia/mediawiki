/*!
 * JavaScript module used on Special:PageLanguage
 */
( function ( $, OO ) {
	$( function () {
		// Select the 'Language select' option if user is trying to select language
		OO.ui.infuse( 'mw-pl-languageselector' ).on( 'change', function () {
			OO.ui.infuse( 'mw-pl-options' ).setValue( '2' );
		} );
	} );
}( jQuery, OO ) );
