/*!
 * JavaScript for Special:Preferences
 */
( function ( mw, $ ) {
	$( function () {
		var allowCloseWindow;

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
