/*!
 * JavaScript for Special:Undelete
 */
jQuery( function ( $ ) {
	$( '#mw-undelete-invert' ).click( function () {
		$( '.mw-undelete-revlist input[type="checkbox"]' ).prop( 'checked', function ( i, val ) {
			return !val;
		} );
	} );
} );
