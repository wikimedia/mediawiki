/*!
 * Animate patrol links to use asynchronous API requests to
 * patrol pages, rather than navigating to a different URI.
 *
 * @since 1.21
 * @author Marius Hoch <hoo@online.de>
 */
( function () {
	if ( !mw.user.tokens.exists( 'patrolToken' ) ) {
		// Current user has no patrol right.
		return;
	}
	$( function () {
		var $patrolLinks = $( '.patrollink[data-mw="interface"] a' );
		$patrolLinks.on( 'click', function ( e ) {
			var $spinner, rcid, api;

			// Preload the notification module for mw.notify
			mw.loader.load( 'mediawiki.notification' );

			// Hide the link and create a spinner to show it inside the brackets.
			$spinner = $.createSpinner( {
				size: 'small',
				type: 'inline'
			} );
			$( this ).hide().after( $spinner );

			rcid = mw.util.getParamValue( 'rcid', this.href );
			api = new mw.Api();

			api.postWithToken( 'patrol', {
				formatversion: 2,
				action: 'patrol',
				rcid: rcid
			} ).done( function ( data ) {
				var title;
				// Remove all patrollinks from the page (including any spinners inside).
				$patrolLinks.closest( '.patrollink' ).remove();
				if ( data.patrol !== undefined ) {
					// Success
					title = new mw.Title( data.patrol.title );
					mw.notify( mw.msg( 'markedaspatrollednotify', title.toText() ) );
				} else {
					// This should never happen as errors should trigger fail
					mw.notify( mw.msg( 'markedaspatrollederrornotify' ), { type: 'error' } );
				}
			} ).fail( function ( code, data ) {
				var $msg = api.getErrorMessage( data );
				$spinner.remove();
				// Restore the patrol link. This allows the user to try again
				// (or open it in a new window, bypassing this ajax module).
				$patrolLinks.show();
				mw.notify( $msg, { type: code === 'noautopatrol' ? 'warn' : 'error' } );
			} );

			e.preventDefault();
		} );
	} );
}() );
