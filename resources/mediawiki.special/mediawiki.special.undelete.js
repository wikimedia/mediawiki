/*
 * JavaScript for Specical:Undelete
 */
jQuery( document ).ready( function ( $ ) {
	$( '#mw-undelete-invert' ).click( function ( e ) {
		e.preventDefault();
		$( '#undelete input[type="checkbox"]' ).prop( 'checked', function ( i, val ) {
			return !val;
		} );
	} );
} );
