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

		// Utility function for looping through each watchlist line that matches certain titles
		function forEachMatchingTitle( titles, callback ) {
			$( '.mw-title' ).each( function ( index, titleEl ) {
				var $row, $titleEl = $( titleEl ), $unwatchLink;

				if ( $.inArray( $titleEl.text(), titles ) > -1 ) {

					$row = $titleEl.closest( 'li, tbody' );
					$unwatchLink = $row.find( '.mw-unwatch-link' );

					callback( $titleEl.text(), $row, $unwatchLink );
				}
			} );
		}

		// Watch/unwatch toggle link:
		// If a page is on the watchlist, a '×' is shown which, when clicked, removes the page from the watchlist.
		// After unwatching a page, the '×' becomes a '+', which if clicked re-watches the page.
		// Unwatched page entries are struck through and have lowered opacity.
		$( '.mw-unwatch-link' ).click( function ( event ) {
			var $unwatchLink = $( this ),
				// EnhancedChangesList uses <tbody> for each row, while
				// OldChangesList uses <li> for each row
				$watchlistLine = $unwatchLink.closest( 'li, tbody' ),
				// Toggle state of watch/unwatch link
				watched = $watchlistLine.data( 'watched' ),
				pageTitle = $watchlistLine.find( '.mw-title' ).text(),
				pageTitleObj = mw.Title.newFromText( pageTitle ),
				pageNamespaceId = pageTitleObj.getNamespaceId(),
				isTalk = pageNamespaceId % 2 === 1,
				associatedPageTitle = new mw.Title( pageTitleObj.getMainText(),
					isTalk ? pageNamespaceId - 1 : pageNamespaceId + 1 ).getPrefixedText();

			event.preventDefault();
			event.stopPropagation();

			if ( watched === undefined ) {
				watched = true;
			}

			// Depending on whether we are watching or unwatching, for each entry of the page (and its associated page i.e. Talk),
			// change the text, tooltip, and non-JS href of the (un)watch button,
			// and update the styling of the watchlist entry.
			if ( watched ) {
				api.unwatch( pageTitle )
					.done( function () {
						forEachMatchingTitle( [ pageTitle, associatedPageTitle ], function ( rowPageTitle, $row, $rowUnwatchLink ) {
							$rowUnwatchLink
								.text( '+' )
								.attr( 'title', mw.msg( 'tooltip-ca-watch' ) )
								.attr( 'href', mw.util.getUrl( rowPageTitle, { action: 'watch' } ) );
							$row.data( 'watched', false );
							$row.find( '.mw-changeslist-line-inner, .mw-enhanced-rc-nested' )
								.addClass( 'mw-changelist-line-inner-unwatched' );
						} );

						mw.notify( mw.message( 'removedwatchtext' + ( isTalk ? '-talk' : '' ),
							pageTitle ), { tag: 'watch-self' } );
					} );
			} else {
				api.watch( pageTitle )
					.done( function () {
						forEachMatchingTitle( [ pageTitle, associatedPageTitle ], function ( rowPageTitle, $row, $rowUnwatchLink ) {
							$rowUnwatchLink
								.text( '×' )
								.attr( 'title', mw.msg( 'tooltip-ca-unwatch' ) )
								.attr( 'href', mw.util.getUrl( rowPageTitle, { action: 'unwatch' } ) );
							$row.data( 'watched', true );
							$row.find( '.mw-changelist-line-inner-unwatched' )
								.removeClass( 'mw-changelist-line-inner-unwatched' );
						} );

						mw.notify( mw.message( 'addedwatchtext' + ( isTalk ? '-talk' : '' ),
							pageTitle ), { tag: 'watch-self' } );
					} );
			}
		} );
	} );

}( mediaWiki, jQuery, OO ) );
