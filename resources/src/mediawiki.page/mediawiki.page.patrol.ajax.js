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
		var $patrolLinks = $( '.patrollink a' ),
			$spinner = $.createSpinner( {
				size: 'small',
				type: 'inline'
			} );

		mw.patrol.setup( $patrolLinks );
		$patrolLinks.on( 'patrol::loading', function( ev, e, status ) {
			// Hide the link show a spinner instead.
			$( e.target ).hide().after( $spinner );

			status.done( function( data ) {
				// Remove all patrollinks from the page (including any spinners inside).
				$patrolLinks.closest( '.patrollink' ).remove();
				if ( data.patrol !== undefined ) {
					// Success
					var title = new mw.Title( data.patrol.title );
					mw.notify( mw.msg( 'markedaspatrollednotify', title.toText() ) );
				} else {
					// This should never happen as errors should trigger fail
					mw.notify( mw.msg( 'markedaspatrollederrornotify' ) );
				}
			} ).fail( function( error ) {
				$spinner.remove();
				// Restore the patrol link. This allows the user to try again
				// (or open it in a new window, bypassing this ajax module).
				$patrolLinks.show();
				if ( error === 'noautopatrol' ) {
					// Can't patrol own
					mw.notify( mw.msg( 'markedaspatrollederror-noautopatrol' ) );
				} else {
					mw.notify( mw.msg( 'markedaspatrollederrornotify' ) );
				}
			} );
		} );
	} );
}( mediaWiki, jQuery ) );
