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
		$patrolLinks.on( 'click', function ( e ) {
			var $spinner, href, rcid, apiRequest;

			// Start preloading the notification module (normally loaded by mw.notify())
			mw.loader.load( ['mediawiki.notification'], null, true );

			// Hide the link and create a spinner to show it inside the brackets.
			$spinner = $.createSpinner( {
				size: 'small',
				type: 'inline'
			} );
			$( this ).hide().after( $spinner );

			href = $( this ).attr( 'href' );
			rcid = mw.util.getParamValue( 'rcid', href );
			apiRequest = new mw.Api();

			apiRequest.postWithToken( 'patrol', {
				action: 'patrol',
				rcid: rcid
			} )
			.done( function ( data ) {
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
			} )
			.fail( function ( error ) {
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

			e.preventDefault();
		} );
	} );
}( mediaWiki, jQuery ) );
