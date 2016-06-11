/*!
 * JavaScript for Special:Watchlist
 */
( function ( mw, $, OO ) {
	$( function () {
		var api = new mw.Api(),
			$progressBar,
			$resetForm = $( '#mw-watchlist-resetbutton' );

		// If the user wants to reset their watchlist, use an API call to do so (no reload required)
		// Adapted from a user script by User:NQ of English Wikipedia
		// (User:NQ/WatchlistResetConfirm.js)
		$resetForm.submit( function ( event ) {
			var $button = $resetForm.find( 'input[name=mw-watchlist-reset-submit]' );

			event.preventDefault();

			// Disable reset button to prevent multiple concurrent requests
			$button.prop( 'disabled', true );

			if ( !$progressBar ) {
				$progressBar = new OO.ui.ProgressBarWidget( { progress: false } ).$element;
				$progressBar.css( {
					position: 'absolute',
					width: '100%'
				} );
			}
			// Show progress bar
			$resetForm.append( $progressBar );

			// Use action=setnotificationtimestamp to mark all as visited,
			// then set all watchlist lines accordingly
			api.postWithToken( 'csrf', {
				formatversion: 2,
				action: 'setnotificationtimestamp',
				entirewatchlist: true
			} ).done( function () {
				// Enable button again
				$button.prop( 'disabled', false );
				// Hide the button because further clicks can not generate any visual changes
				$button.css( 'visibility', 'hidden' );
				$progressBar.detach();
				$( '.mw-changeslist-line-watched' )
					.removeClass( 'mw-changeslist-line-watched' )
					.addClass( 'mw-changeslist-line-not-watched' );
			} ).fail( function () {
				// On error, fall back to server-side reset
				// First remove this submit listener and then re-submit the form
				$resetForm.off( 'submit' ).submit();
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

		// add a listener on all form elements in the header form
		$( '.mw-unwatch-link' ).click( function ( event ) {
			var $unwatchLink = $( this ),
				// EnhancedChangesList uses <tbody> for each row, while
				// OldChangesList uses <li> for each row
				$watchlistLine = $unwatchLink.closest( 'li, tbody' ),
				$title = $watchlistLine.find( '.mw-title' ),
				pageTitle = $title.text(),
				watched = $watchlistLine.data( 'watched' );

			if ( watched === undefined ) {
				watched = true;
			}

			event.preventDefault();
			event.stopPropagation();

			if ( watched ) {
				api.unwatch( pageTitle )
					.done( function () {
						// hide all watchlist entries with matching title and show undo link
						$( '.mw-title' ).each( function ( index, titleEl ) {
							var $row, $titleEl = $( titleEl ), $unwatch;
							if ( $titleEl.text() === pageTitle ) {
								$row = $titleEl.closest( 'li, tbody' );
								$unwatch = $row.find( '.mw-unwatch-link' );

								$unwatch.text( '+' );
								$row.data( 'watched', false );
								$row.find( '.mw-changeslist-line-inner, .mw-enhanced-rc-nested' )
									.addClass( 'mw-changelist-line-inner-unwatched' );
							}
						} );

						mw.notify( mw.message( 'removedwatchtext', pageTitle ), { tag: 'watch-self' } );
					} );
			} else {
				api.watch( pageTitle )
					.done( function () {
						// hide all watchlist entries with matching title and show undo link
						$( '.mw-title' ).each( function ( index, titleEl ) {
							var $row, $titleEl = $( titleEl ), $unwatch;
							if ( $titleEl.text() === pageTitle ) {
								$row = $titleEl.closest( 'li, tbody' );
								$unwatch = $row.find( '.mw-unwatch-link' );

								$unwatch.text( '×' );
								$row.data( 'watched', true );
								$row.find( '.mw-changelist-line-inner-unwatched' )
									.removeClass( 'mw-changelist-line-inner-unwatched' );
							}
						} );

						mw.notify( mw.message( 'addedwatchtext', pageTitle ), { tag: 'watch-self' } );
					} );
			}
		} );
	} );

}( mediaWiki, jQuery, OO ) );
