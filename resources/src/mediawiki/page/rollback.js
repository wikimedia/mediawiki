/*!
 * Enhance rollback links by using asynchronous API requests,
 * rather than navigating to an action page.
 *
 * @since 1.27
 * @author Timo Tijhof
 */
( function ( mw, $ ) {

	$( function () {
		$( '.mw-rollback-link a' ).click( function ( e ) {
			var api,
				url = this.href,
				page = mw.util.getParamValue( 'title', url ),
				user = mw.util.getParamValue( 'from', url );

			if ( !page || !user ) {
				// Let native browsing handle the link
				return true;
			}

			// Preload the notification module (lazy-loaded via mw.notify)
			mw.loader.load( 'mediawiki.notification' );

			api = new mw.Api();
			api.rollback( page, user )
				.then( function () {
					mw.notify( 'Rollback success.' );
				}, function () {
					mw.notify( 'Rollback failed!' );
				} );

			e.preventDefault();
		} );
	} );

}( mediaWiki, jQuery ) );
