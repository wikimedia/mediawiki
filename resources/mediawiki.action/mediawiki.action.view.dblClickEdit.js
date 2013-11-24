/**
 * This module enables double-click-to-edit functionality.
 */
( function ( mw, $ ) {
	$( function () {
		mw.util.$content.dblclick( function ( e ) {
			e.preventDefault();
			// Trigger native HTMLElement click instead of opening URL (bug 43052)
			$( '#ca-edit a' ).get( 0 ).click();
		} );
	} );
}( mediaWiki, jQuery ) );
