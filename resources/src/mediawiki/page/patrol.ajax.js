/*!
 * Animate patrol links to use asynchronous API requests to
 * patrol pages, rather than navigating to a different URI.
 *
 * @since 1.21
 * @author Marius Hoch <hoo@online.de>
 * @author Timo Tijhof <krinklemail@gmail.com>
 * @author Florian Schmidt <florian.schmidt.welzow@t-online.de>
 */
( function ( mw, $ ) {
	if ( !mw.user.tokens.exists( 'patrolToken' ) ) {
		// Current user has no patrol right, or an old cached version of user.tokens
		// that didn't have patrolToken yet.
		return;
	}
	$( function () {
		var $patrolLinks = $( '.patrollink a' );

		mw.page.patrol.setup( $patrolLinks ).on( 'patrol', function ( promise, node ) {
			// Hide the link show a spinner instead.
			var $spinner = $.createSpinner( {
				size: 'small',
				type: 'inline'
			} );
			$( node ).hide().after( $spinner );
			// Preload the notification module (required by mw.notify)
			mw.loader.load( 'mediawiki.notification', null, true );

			promise.then(
				function ( data ) {
					// Remove all patrol links from the page (including any spinners inside)
					$patrolLinks.closest( '.patrollink' ).remove();
					if ( data.patrol !== undefined ) {
						// Success
						var title = new mw.Title( data.patrol.title );
						mw.notify( mw.msg( 'markedaspatrollednotify', title.toText() ) );
					} else {
						// This should never happen as errors should trigger fail
						mw.notify( mw.msg( 'markedaspatrollederrornotify' ) );
					}
				}, function ( error ) {
					$spinner.remove();
					// Restore the patrol link. This allows the user to try again
					$patrolLinks.show();
					if ( error === 'noautopatrol' ) {
						// Can't patrol own
						mw.notify( mw.msg( 'markedaspatrollederror-noautopatrol' ) );
					} else {
						mw.notify( mw.msg( 'markedaspatrollederrornotify' ) );
					}
				}
			);
		} );
	} );
}( mediaWiki, jQuery ) );
