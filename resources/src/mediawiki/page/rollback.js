/*!
 * Enhance rollback links by using asynchronous API requests,
 * rather than navigating to an action page.
 *
 * @since 1.27
 * @author Timo Tijhof
 */
( function ( mw, $ ) {

	$( function () {
		$( '.mw-rollback-link' ).on( 'click', 'a[data-mw="interface"]', function ( e ) {
			var api,
				url = this.href,
				page = mw.util.getParamValue( 'title', url ),
				user = mw.util.getParamValue( 'from', url );

			if ( !page || !user ) {
				// Let browser handle the link
				return true;
			}

			// Preload the notification module (required by mw.notify)
			mw.loader.load( 'mediawiki.notification' );

			api = new mw.Api();
			api.rollback( page, user )
				.then( function ( data ) {
					mw.notify( mw.msg( 'rollback-success', data.oldUser, data.lastUser ) );
					// FIXME: This leaves a stray " | " between where the rollback link was
					// and the undo link
					// FIXME: Display link to the diff.
					$( e.delegateTarget ).remove();
				}, function () {
					// FIXME: Use a less generic error (same as RollbackAction.php does)
					mw.notify( mw.msg( 'rollbackfailed' ) );
				} );

			e.preventDefault();
		} );
	} );

}( mediaWiki, jQuery ) );
