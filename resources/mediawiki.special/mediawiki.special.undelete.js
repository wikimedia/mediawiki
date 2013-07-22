/**
 * JavaScript for Special:Undelete
 */
jQuery( function ( $ ) {
	$( '#mw-undelete-invert' ).click( function ( e ) {
		$( '#undelete input[type="checkbox"]' ).prop( 'checked', function ( i, val ) {
			return !val;
		} );
		e.preventDefault();
	} );
} );
