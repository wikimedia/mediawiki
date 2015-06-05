/*!
 * Animate patrol links to use asynchronous API requests to
 * patrol pages, rather than navigating to a different URI.
 *
 * @since 1.21
 * @author Marius Hoch <hoo@online.de>
 */
( function ( mw, $ ) {
	if ( !mw.user.tokens.exists( 'patrolToken' ) ) {
		// Current user has no patrol right, or an old cached version of user.tokens
		// that didn't have patrolToken yet.
		return;
	}
	$( function () {
		var $patrolLinks = $( '.patrollink a' );
		mw.patrol.setup( {
			links: $patrolLinks,
			spinner: $.createSpinner( {
				size: 'small',
				type: 'inline'
			} )
		} );
	} );
}( mediaWiki, jQuery ) );
