/**
 * JavaScript for Special:Undelete
 */
jQuery( document ).ready( function ( $ ) {
	$( '#mw-undelete-invert' ).click( function ( e ) {
		$( '#undelete input[type="checkbox"]' ).prop( 'checked', function ( i, val ) {
			return !val;
		} );
		e.preventDefault();
	} );
} );
