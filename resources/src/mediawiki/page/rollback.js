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
			// Remove event handler so that next click (re-try) uses server action
			$( e.delegateTarget ).off( 'click' );

			api = new mw.Api();
			api.rollback( page, user )
				.then( function ( data ) {
					mw.notify( mw.msg( 'rollback-success', data.oldUser, data.lastUser ) );
					// FIXME: This leaves a stray " | " between where the rollback link was
					// and the undo link
					$( e.delegateTarget ).remove();
				}, function () {
					// Can't display detailed error because oldUser/lastUser data is only
					// exposed on success.
					mw.notify( mw.msg( 'rollbackfailed' ), { type: 'error' } );
				} );

			e.preventDefault();
		} );
	} );

}( mediaWiki, jQuery ) );
