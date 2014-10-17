/*!
 * .hlist fallbacks for IE 8.
 * @author [[User:Edokter]]
 */
( function ( mw, $ ) {
	var profile = $.client.profile();

	if ( profile.name === 'msie' && profile.versionNumber === 8 ) {
		/* Add pseudo-selector class to last-child list items */
		mw.hook( 'wikipage.content' ).add( function ( $content ) {
			$content.find( '.hlist' ).find( 'dd:last-child, dt:last-child, li:last-child' )
				.addClass( 'hlist-last-child' );
		} );
	}
}( mediaWiki, jQuery ) );
