/*!
 * JavaScript for Special:Watchlist
 */
console.log( 0 );
( function ( mw, $ ) {
	console.log( 1 );
	$( function () {
		console.log( 2 );
		$( '.changeslist-line-watched' ).on( 'click', function () {
			console.log( 3 );
			$( this ).removeClass( 'changeslist-line-watched' );
		} );
	} );

} ) ( mediaWiki, jQuery );
