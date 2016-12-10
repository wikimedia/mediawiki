/*!
 * JavaScript for Special:Watchlist
 */
( function ( mw, $ ) {
	$( function () {
		var $watchlistReset, messageDialog, windowManager;

		// If the user wants to reset their watchlist, use an API call to do so (no reload required)
		// Adapted from a user script by User:NQ of English Wikipedia
		// (User:NQ/WatchlistResetConfirm.js)
		$watchlistReset = $( '#mw-watchlist-resetbutton' );
		windowManager = new OO.ui.WindowManager();
		$( 'body' ).append( windowManager.$element );

		// Confirmation dialog
		messageDialog = new OO.ui.MessageDialog();
		windowManager.addWindows( [ messageDialog ] );

		$watchlistReset.submit( function ( event ) {
			event.preventDefault();

			windowManager.openWindow( messageDialog, {
				title: mw.msg( 'enotif_reset' ),
				message: mw.msg( 'watchlist-mark-all-visited' ),
				actions: [
					{
						action: 'reject',
						label: mw.msg( 'cancel' ),
						flags: [ 'safe' ]
					},
					{
						action: 'reset',
						label: mw.msg( 'confirm' ),
						flags: [ 'primary', 'destructive' ]
					}
				]
			} ).then( function ( opened ) {
				opened.then( function ( closing, data ) {
					if ( data && data.action === 'reset' ) {
						// Use action=setnotificationtimestamp to mark all as visited,
						// then set all watchlist lines accordingly
						new mw.Api().post( {
							action: 'setnotificationtimestamp',
							entirewatchlist: '',
							token: mw.user.tokens.get( 'editToken' )
						} ).done( function () {
							$( '.mw-changeslist-line-watched' )
								.removeClass( 'mw-changeslist-line-watched' )
								.addClass( 'mw-changeslist-line-not-watched' );
						} );

					}
				} );
			} );
		} );

		// if the user wishes to reload the watchlist whenever a filter changes
		if ( mw.user.options.get( 'watchlistreloadautomatically' ) ) {
			// add a listener on all form elements in the header form
			$( '#mw-watchlist-form input, #mw-watchlist-form select' ).on( 'change', function () {
				// submit the form when one of the input fields is modified
				$( '#mw-watchlist-form' ).submit();
			} );
		}
	} );

}( mediaWiki, jQuery )
);
