/*!
 * JavaScript for Special:Watchlist
 */
( function () {
	$( function () {
		var api = new mw.Api(), $progressBar, $resetForm = $( '#mw-watchlist-resetbutton' );

		// If the user wants to reset their watchlist, use an API call to do so (no reload required)
		// Adapted from a user script by User:NQ of English Wikipedia
		// (User:NQ/WatchlistResetConfirm.js)
		$resetForm.on( 'submit', function ( event ) {
			var $button = $resetForm.find( 'input[name=mw-watchlist-reset-submit]' );

			event.preventDefault();

			// Disable reset button to prevent multiple concurrent requests
			$button.prop( 'disabled', true );

			if ( !$progressBar ) {
				$progressBar = new OO.ui.ProgressBarWidget( { progress: false } ).$element;
				$progressBar.css( {
					position: 'absolute', width: '100%'
				} );
			}
			// Show progress bar
			$resetForm.append( $progressBar );

			// Use action=setnotificationtimestamp to mark all as visited,
			// then set all watchlist lines accordingly
			api.postWithToken( 'csrf', {
				formatversion: 2, action: 'setnotificationtimestamp', entirewatchlist: true
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
				$resetForm.off( 'submit' ).trigger( 'submit' );
			} );
		} );

		// if the user wishes to reload the watchlist whenever a filter changes
		if ( mw.user.options.get( 'watchlistreloadautomatically' ) ) {
			// add a listener on all form elements in the header form
			$( '#mw-watchlist-form input, #mw-watchlist-form select' ).on( 'change', function () {
				// submit the form when one of the input fields is modified
				$( '#mw-watchlist-form' ).trigger( 'submit' );
			} );
		}

		if ( mw.user.options.get( 'watchlistunwatchlinks' ) ) {
			// Watch/unwatch toggle link:
			// If a page is on the watchlist, a '×' is shown which, when clicked, removes the page from the watchlist.
			// After unwatching a page, the '×' becomes a '+', which if clicked re-watches the page.
			// Unwatched page entries are struck through and have lowered opacity.
			$( '.mw-changeslist' ).on( 'click', '.mw-unwatch-link, .mw-watch-link', function ( event ) {
				var $unwatchLink = $( this ), // EnhancedChangesList uses <table> for each row, while OldChangesList uses <li> for each row
					$watchlistLine = $unwatchLink.closest( 'li, table' )
						.find( '[data-target-page]' ),
					pageTitle = $watchlistLine.data( 'targetPage' ),
					isTalk = mw.Title.newFromText( pageTitle ).isTalkPage();

				// Utility function for looping through each watchlist line that matches
				// a certain page or its associated page (e.g. Talk)
				function forEachMatchingTitle( title, callback ) {

					var titleObj = mw.Title.newFromText( title ),
						associatedTitleObj = titleObj.isTalkPage() ? titleObj.getSubjectPage() : titleObj.getTalkPage(),
						associatedTitle = associatedTitleObj.getPrefixedText();
					$( '.mw-changeslist-line' ).each( function () {
						var $this = $( this ), $row, $unwatchLink;

						$this.find( '[data-target-page]' ).each( function () {
							var $this = $( this ), rowTitle = $this.data( 'targetPage' );
							if ( rowTitle === title || rowTitle === associatedTitle ) {

								// EnhancedChangesList groups log entries by performer rather than target page. Therefore...
								// * If using OldChangesList, use the <li>
								// * If using EnhancedChangesList and $this is part of a grouped log entry, use the <td> sub-entry
								// * If using EnhancedChangesList and $this is not part of a grouped log entry, use the <table> grouped entry
								$row =
									$this.closest(
										'li, table.mw-collapsible.mw-changeslist-log td[data-target-page], table' );
								$unwatchLink = $row.find( '.mw-unwatch-link, .mw-watch-link' );

								callback( rowTitle, $row, $unwatchLink );
							}
						} );
					} );
				}

				// Preload the notification module for mw.notify
				mw.loader.load( 'mediawiki.notification' );

				// Depending on whether we are watching or unwatching, for each entry of the page (and its associated page i.e. Talk),
				// change the text, tooltip, and non-JS href of the (un)watch button, and update the styling of the watchlist entry.
				if ( $unwatchLink.hasClass( 'mw-unwatch-link' ) ) {
					api.unwatch( pageTitle )
						.done( function () {
							forEachMatchingTitle( pageTitle,
								function ( rowPageTitle, $row, $rowUnwatchLink ) {
									$rowUnwatchLink
										.text( mw.msg( 'watchlist-unwatch-undo' ) )
										.attr( 'title', mw.msg( 'tooltip-ca-watch' ) )
										.attr( 'href',
											mw.util.getUrl( rowPageTitle, { action: 'watch' } ) )
										.removeClass( 'mw-unwatch-link loading' )
										.addClass( 'mw-watch-link' );
									$row.find(
										'.mw-changeslist-line-inner, .mw-enhanced-rc-nested' )
										.addBack( '.mw-enhanced-rc-nested' ) // For matching log sub-entry
										.addClass( 'mw-changelist-line-inner-unwatched' );
								} );

							mw.notify(
								mw.message( isTalk ? 'removedwatchtext-talk' : 'removedwatchtext',
									pageTitle ), { tag: 'watch-self' } );
						} );
				} else {
					api.watch( pageTitle )
						.then( function () {
							forEachMatchingTitle( pageTitle,
								function ( rowPageTitle, $row, $rowUnwatchLink ) {
									$rowUnwatchLink
										.text( mw.msg( 'watchlist-unwatch' ) )
										.attr( 'title', mw.msg( 'tooltip-ca-unwatch' ) )
										.attr( 'href',
											mw.util.getUrl( rowPageTitle, { action: 'unwatch' } ) )
										.removeClass( 'mw-watch-link loading' )
										.addClass( 'mw-unwatch-link' );
									$row.find( '.mw-changelist-line-inner-unwatched' )
										.addBack( '.mw-enhanced-rc-nested' )
										.removeClass( 'mw-changelist-line-inner-unwatched' );
								} );

							mw.notify(
								mw.message( isTalk ? 'addedwatchtext-talk' : 'addedwatchtext',
									pageTitle ), { tag: 'watch-self' } );
						} );
				}

				event.preventDefault();
				event.stopPropagation();
				$unwatchLink.trigger( 'blur' );
			} );
		}
	} );

}() );
