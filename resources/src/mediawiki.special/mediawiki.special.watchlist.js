/*
 * JavaScript for Special:Watchlist
 */
( function ( mw, $ ) {
	// add a listener on all form elements in the header form
	$( '.mw-unwatch-link' ).click( function ( event ) {
		var $unwatchLink = $( event.toElement ),
			$watchlistLine = $unwatchLink.parent(),
			$title = $watchlistLine.find( '.mw-title' ),
			pageTitle = $title.text(),
			api = new mw.Api();

		event.preventDefault();
		event.stopPropagation();

		api[ 'unwatch' ]( pageTitle )
			.done( function () {
				$( '.mw-title' ).each( function ( index, el ) {
					if ( el.innerText === pageTitle ) {
						// TODO: add undo
						el.innerText = 'Removed';
					}
				} );
			} );


	} );

}( mediaWiki, jQuery ) );
