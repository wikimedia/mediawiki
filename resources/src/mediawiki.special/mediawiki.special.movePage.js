/*!
 * JavaScript for Special:MovePage
 */
( function ( mw, $ ) {
	$( function () {
		// Infuse for pretty dropdown
		OO.ui.infuse( $( '#wpNewTitle' ) );
		// Limit to bytes, not characters
		mw.widgets.visibleByteLimit( OO.ui.infuse( $( '#wpReason' ) ) );
		// Infuse for nicer "help" popup
		if ( $( '#wpMovetalk-field' ).length ) {
			OO.ui.infuse( $( '#wpMovetalk-field' ) );
		}
	} );
}( mediaWiki, jQuery ) );
