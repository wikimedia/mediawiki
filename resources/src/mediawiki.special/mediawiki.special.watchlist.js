/*!
 * JavaScript for Special:Watchlist
 */
( function ( mw, $ ) {
	$( function () {
		// If the user wants to reset their watchlist, use an API call to do so (no reload required)
		// Adapted from a user script by User:NQ of English Wikipedia
		// (User:NQ/WatchlistResetConfirm.js)
		$( '#mw-watchlist-resetbutton' ).submit( function ( event ) {
			var $form = $( this );
			event.preventDefault();

			OO.ui.confirm( mw.msg( 'watchlist-mark-all-visited' ) ).done( function ( confirmed ) {
				if ( confirmed ) {
					var $button = $form.find( 'input[name=dummy]' );
					console.log( $button );

					// Disable reset button to prevent multiple requests
					$button.prop( 'disabled', true );

					// Use action=setnotificationtimestamp to mark all as visited,
					// then set all watchlist lines accordingly
					new mw.Api().postWithEditToken( {
						action: 'setnotificationtimestamp',
						entirewatchlist: ''
					} ).done( function () {
						$button.prop( 'disabled', false );
						$( '.mw-changeslist-line-watched' )
							.removeClass( 'mw-changeslist-line-watched' )
							.addClass( 'mw-changeslist-line-not-watched' );
					} ).fail( function () {
						// On error, fall back to server-side reset
						// First remove this submit listener and then re-submit the form
						$form.off( 'submit' ).submit();
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

}( mediaWiki, jQuery ) );
