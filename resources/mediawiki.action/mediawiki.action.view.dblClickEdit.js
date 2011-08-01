/**
 * This module enables double-click-to-edit functionality
 */
jQuery( document ).ready( function( $ ) {
	var url = $( '#ca-edit a' ).attr( 'href' );
	if ( url ) {
		mw.util.$content.dblclick( function( e ) {
			e.preventDefault();
			window.location = url;
		} );
	}
} );
