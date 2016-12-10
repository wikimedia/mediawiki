/*!
 * JavaScript for Special:Watchlist
 */
( function ( mw, $, OO ) {
	$( function () {
		var $resetForm = $( '#mw-watchlist-resetbutton' ),
			$progressBar = OO.ui.ProgressBarWidget.static.infuse( 'mw-watchlist-reset-progressbar' ).$element;

		// If the user wants to reset their watchlist, use an API call to do so (no reload required)
		// Adapted from a user script by User:NQ of English Wikipedia
		// (User:NQ/WatchlistResetConfirm.js)
		$resetForm.submit( function ( event ) {
			event.preventDefault();

			OO.ui.confirm( mw.msg( 'watchlist-mark-all-visited' ) ).done( function ( confirmed ) {
				var $button;

				if ( confirmed ) {
					// Disable reset button to prevent multiple requests and show progress bar
					$button = $resetForm.find( 'input[name=mw-watchlist-reset-submit]' ).prop( 'disabled', true );
					$progressBar.css( 'visibility', 'visible' );

					// Use action=setnotificationtimestamp to mark all as visited,
					// then set all watchlist lines accordingly
					new mw.Api().postWithToken( 'csrf', {
						formatversion: 2,
						action: 'setnotificationtimestamp',
						entirewatchlist: true
					} ).done( function () {
						// Hide button and progress bar but leave empty space to keep interface consistent
						$button.css( 'visibility', 'hidden' );
						$progressBar.css( 'visibility', 'hidden' );

						$( '.mw-changeslist-line-watched' )
							.removeClass( 'mw-changeslist-line-watched' )
							.addClass( 'mw-changeslist-line-not-watched' );
					} ).fail( function () {
						// On error, fall back to server-side reset
						// First remove this submit listener and then re-submit the form
						$resetForm.off( 'submit' ).submit();
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

}( mediaWiki, jQuery, OO ) );
