/*!
 * .hlist fallbacks for IE 6, 7 and 8.
 * @author [[User:Edokter]]
 */
( function ( mw, $ ) {
	var profile = $.client.profile();

	if ( profile.name === 'msie' ) {
		if ( profile.versionNumber === 8 ) {
			/* IE 8: Add pseudo-selector class to last-child list items */
			mw.hook( 'wikipage.content' ).add( function ( $content ) {
				$content.find( '.hlist' ).find( 'dd:last-child, dt:last-child, li:last-child' )
					.addClass( 'hlist-last-child' );
			} );
		}
		else if ( profile.versionNumber <= 7 ) {
			/* IE 7 and below: Generate interpuncts and parentheses */
			mw.hook( 'wikipage.content' ).add( function ( $content ) {
				var $hlists = $content.find( '.hlist' );
				$hlists.find( 'dt:not(:last-child)' )
					.append( ': ' );
				$hlists.find( 'dd:not(:last-child)' )
					.append( '<b>·</b> ' );
				$hlists.find( 'li:not(:last-child)' )
					.append( '<b>·</b> ' );
				$hlists.find( 'dl dl, dl ol, dl ul, ol dl, ol ol, ol ul, ul dl, ul ol, ul ul' )
					.prepend( '( ' ).append( ') ' );
			} );
		}
	}
}( mediaWiki, jQuery ) );
