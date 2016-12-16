/*!
 * JavaScript for Special:Watchlist
 */
( function ( mw, $, OO ) {
	$( function () {
		var $progressBar, $resetForm = $( '#mw-watchlist-resetbutton' );

		// If the user wants to reset their watchlist, use an API call to do so (no reload required)
		// Adapted from a user script by User:NQ of English Wikipedia
		// (User:NQ/WatchlistResetConfirm.js)
		$resetForm.submit( function ( event ) {
			event.preventDefault();

			OO.ui.confirm( mw.msg( 'watchlist-mark-all-visited' ) )
			.done( function ( confirmed ) {
				var $button;

				if ( !confirmed ) {
					return;
				}

				// Disable reset button to prevent multiple requests
				$button = $resetForm.find( 'input[name=mw-watchlist-reset-submit]' );
				$button.prop( 'disabled', true );

				// Show progress bar
				if ( $progressBar ) {
					$progressBar.css( 'visibility', 'visible' );
				} else {
					$progressBar = new OO.ui.ProgressBarWidget( { progress: false } ).$element;
					$progressBar.css( {
						position: 'absolute',
						width: '100%'
					} );
					$resetForm.append( $progressBar );
				}

				// Use action=setnotificationtimestamp to mark all as visited,
				// then set all watchlist lines accordingly
				new mw.Api().postWithToken( 'csrf', {
					formatversion: 2,
					action: 'setnotificationtimestamp',
					entirewatchlist: true
				} ).done( function () {
					// Enable button again
					$button.prop( 'disabled', false );
					// Hide the button because further clicks can not generate any visual changes
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
