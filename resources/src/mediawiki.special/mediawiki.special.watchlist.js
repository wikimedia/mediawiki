/*!
 * JavaScript for Special:Watchlist
 */
( function ( mw, $ ) {
	$( function () {
		var $watchlistReset;

		// If the user wants to reset their watchlist, use an API call to do so (no reload required)
		// Adapted from a user script by User:NQ of English Wikipedia
		// (User:NQ/WatchlistResetConfirm.js)
		$watchlistReset = $( '#mw-watchlist-resetbutton' );
		$watchlistReset.submit( function ( event ) {
			event.preventDefault();

			OO.ui.confirm( mw.msg( 'watchlist-mark-all-visited' ) ).done( function ( confirmed ) {
				if ( confirmed ) {
					// Use action=setnotificationtimestamp to mark all as visited,
					// then set all watchlist lines accordingly
					new mw.Api().postWithEditToken( {
						action: 'setnotificationtimestamp',
						entirewatchlist: ''
					} ).done( function () {
						$( '.mw-changeslist-line-watched' )
							.removeClass( 'mw-changeslist-line-watched' )
							.addClass( 'mw-changeslist-line-not-watched' );
					} );

				}
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
