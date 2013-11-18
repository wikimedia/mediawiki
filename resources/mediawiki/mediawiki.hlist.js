/**
	IE 8: Add pseudo-selector class to last-child list items
	@author [[User:Edokter]] 
*/
jQuery(function( $ ) {
	if ( $.client.profile().name === 'msie' ) {
		if ( $.client.profile().versionNumber === 8 ) {
			$( '.hlist' ).find( 'dd:last-child, dt:last-child, li:last-child' )
				.addClass( 'hlist-last-child' );
		}
			/* IE 7 and below: Generate interpuncts and parentheses */
		if ( $.client.profile().versionNumber <= 7 ) {
			var $hlists = $( '.hlist' );
			$hlists.find( 'dt:not(:last-child)' )
				.append( ': ' );
			$hlists.find( 'dd:not(:last-child)' )
				.append( '<b>·</b> ' );
			$hlists.find( 'li:not(:last-child)' )
				.append( '<b>·</b> ' );
			$hlists.find( 'dl dl, dl ol, dl ul, ol dl, ol ol, ol ul, ul dl, ul ol, ul ul' )
				.prepend( '( ' ).append( ') ' );
		}
	}
} );
