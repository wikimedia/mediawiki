/*!
 * JavaScript for Special:Watchlist
 */
( function ( mw, $ ) {
	var api = new mw.Api;
	$( function () {
		// add a listener on all form elements in the header form
		$( '.mw-unwatch-link' ).click( function ( event ) {
			var $unwatchLink = $( this ),
				$watchlistLine = $unwatchLink.parent(),
				$title = $watchlistLine.find( '.mw-title' ),
				pageTitle = $title.text();

			event.preventDefault();
			event.stopPropagation();

			api.unwatch( pageTitle )
				.done( function () {
					// hide all watchlist entries with matching title and show undo link
					$( '.mw-title' ).each( function ( index, titleEl ) {
						var $row, $titleEl = $( titleEl );
						if ( $titleEl.text() === pageTitle ) {
							$row = $titleEl.parent();
							$row.hide();
							$row.parent().append( $( '<td>' ).append(
								$( '<span>' ).html(
									mw.message( 'removedwatchtext', pageTitle ).parse() + ' '
								).addClass( 'mw-unwatch-text' )
							).append(
								$( '<span>' ).html(
									mw.message( 'watchlist-unwatch-undo', pageTitle ).parse()
								).addClass( 'mw-unwatch-undo-link' )
							) );
						}
					} );

					mw.notify( mw.message( 'removedwatchtext', pageTitle ), { tag: 'watch-self' } );
				} );
		} );

		$( '.mw-changeslist' ).on( 'click', '.mw-unwatch-undo-link', function ( event ) {
			var $watchLink = $( this ),
				$title = $watchLink.parent().parent().find( '.mw-title' ),
				pageTitle = $title.text(),
				api = new mw.Api();

			event.preventDefault();
			event.stopPropagation();

			api.watch( pageTitle )
				.done( function () {
					// unhide all watchlist entries with matching title
					$( '.mw-title' ).each( function ( index, titleEl ) {
						var $row, $titleEl = $( titleEl );
						if ( $titleEl.text() === pageTitle ) {
							$row = $titleEl.parent();
							$row.show();
							$row.parent().find( '.mw-unwatch-undo-link' ).parent().remove();
						}
					} );

					mw.notify( mw.message( 'addedwatchtext', pageTitle ), { tag: 'watch-self' } );
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
