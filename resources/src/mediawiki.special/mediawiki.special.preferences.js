/*!
 * JavaScript for Special:Preferences
 */
( function ( mw, $ ) {
	$( function () {
		var $checkBoxes, allowCloseWindow;

		// To disable all 'namespace' checkboxes in Search preferences
		// when 'Search in all namespaces' checkbox is ticked.
		var $checkBoxes = $( '#mw-htmlform-advancedsearchoptions input[id^=mw-input-wpsearchnamespaces]' );
		if ( $( '#mw-input-wpsearcheverything' ).prop( 'checked' ) ) {
			$checkBoxes.prop( 'disabled', true );
		}
		$( '#mw-input-wpsearcheverything' ).change( function () {
			$checkBoxes.prop( 'disabled', $( this ).prop( 'checked' ) );
		} );

		// Set up a message to notify users if they try to leave the page without
		// saving.
		$( '#mw-prefs-form' ).data( 'origdata', $( '#mw-prefs-form' ).serialize() );
		allowCloseWindow = mw.confirmCloseWindow( {
			test: function () {
				return $( '#mw-prefs-form' ).serialize() !== $( '#mw-prefs-form' ).data( 'origdata' );
			},

			message: mw.msg( 'prefswarning-warning', mw.msg( 'saveprefs' ) ),
			namespace: 'prefswarning'
		} );
		$( '#mw-prefs-form' ).submit( $.proxy( allowCloseWindow, 'release' ) );
		$( '#mw-prefs-restoreprefs' ).click( $.proxy( allowCloseWindow, 'release' ) );
	} );
}( mediaWiki, jQuery ) );
