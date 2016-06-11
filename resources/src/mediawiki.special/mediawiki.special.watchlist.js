/*
 * JavaScript for Special:Watchlist
 */
( function ( mw, $ ) {
	$( function () {
		// add a listener on all form elements in the header form
		$( '.mw-unwatch-link' ).click( function ( event ) {
			var $unwatchLink = $( event.toElement ),
				$watchlistLine = $unwatchLink.parent(),
				$title = $watchlistLine.find( '.mw-title' ),
				pageTitle = $title.text(),
				api = new mw.Api();

			event.preventDefault();
			event.stopPropagation();

			api.unwatch( pageTitle )
				.done( function () {
					// hide all watchlist entries with matching title and show undo link
					$( '.mw-title' ).each( function ( index, titleEl ) {
						var $titleEl = $( titleEl );
						if ( $titleEl.text() === pageTitle ) {
							var $row = $titleEl.parent();
							$row.hide();
							$row.parent().append( $( '<td>' ).text(
								pageTitle + ' unwatched. ' // TODO move to i18n msg
							).append(
								$( '<a>' ).text( 'Undo?' ).addClass( 'mw-watch-link' ).attr( 'href', 'test' )
							) );
						}
					} );
				} );


		} );

		$( '.mw-changeslist' ).on( 'click', '.mw-watch-link', function ( event ) {
			var $watchLink = $( event.toElement ),
				$title, pageTitle, api;

			$title = $watchLink.parent().parent().find( '.mw-title' );
			pageTitle = $title.text();
			api = new mw.Api();

			event.preventDefault();
			event.stopPropagation();

			api.watch( pageTitle )
				.done( function () {
					// unhide all watchlist entries with matching title
					$( '.mw-title' ).each( function ( index, titleEl ) {
						var $titleEl = $( titleEl );
						if ( $titleEl.text() === pageTitle ) {
							var $row = $titleEl.parent();
							$row.show();
							$row.parent().find( '.mw-watch-link' ).parent().remove();
						}
					} );
				} );
		} );
	} );

}( mediaWiki, jQuery ) );
