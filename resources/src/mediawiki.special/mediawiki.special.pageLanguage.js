( function ( $ ) {
	$( document ).ready( function () {

		$( '#mw-pl-languageselector' ).on( 'click', function () {
			var langCode;

			// Select the 'Language select' option if user is trying to select language
			$( '#mw-pl-options-2' ).prop( 'checked', true );

			// Get the language code in the hidden form field
			langCode =  $( '#mw-pl-languageselector' ).val();
			$( '#mw-pl-languagevalue' ).val( langCode );
		} );
	} );
} ( jQuery ) );
