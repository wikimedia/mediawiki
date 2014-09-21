( function ( $ ) {
	$( document ).ready( function () {

		// Select the 'Language select' option if user is trying to select language
		$( '#mw-pl-languageselector' ).on( 'click', function () {
			$( '#mw-pl-options-2' ).prop( 'checked', true );
		} );
	} );
}( jQuery ) );
