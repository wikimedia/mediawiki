/*!
 * JavaScript for Special:Watchlist
 */
( function ( $ ) {
	$( function () {
		$( '.mw-changeslist-line-watched .mw-title a' ).on( 'click', function () {
			$( this )
				.closest( '.mw-changeslist-line-watched' )
				.removeClass( 'mw-changeslist-line-watched' );
		} );
	} );
}( jQuery ) );
