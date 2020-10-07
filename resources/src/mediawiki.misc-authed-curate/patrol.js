/*!
 * Animate patrol links to use asynchronous API requests to
 * patrol pages, rather than navigating to a different URI.
 *
 * @since 1.21
 * @author Marius Hoch <hoo@online.de>
 */
( function () {
	function patrol( link ) {
		var $spinner,
			api = new mw.Api();

		// Preload a module concurrently with the ajax request.
		mw.loader.load( 'mediawiki.notification' );

		// Hide the link and show a spinner inside the brackets.
		$spinner = $.createSpinner( { size: 'small', type: 'inline' } );
		$( link ).css( 'display', 'none' ).after( $spinner );

		api.postWithToken( 'patrol', {
			formatversion: 2,
			action: 'patrol',
			rcid: mw.util.getParamValue( 'rcid', link.href )
		} )
			.then( function ( data ) {
				var title = new mw.Title( data.patrol.title );
				mw.notify( mw.msg( 'markedaspatrollednotify', title.toText() ) );
				// Remove link wrapper (including the spinner).
				$( link ).closest( '.patrollink' ).remove();
			} )
			.catch( function ( code, data ) {
				// Restore the link. This allows the user to try again
				// (or open it in a new window, bypassing this ajax handler).
				$spinner.remove();
				$( link ).css( 'display', '' );

				mw.notify(
					api.getErrorMessage( data ),
					{ type: code === 'noautopatrol' ? 'warn' : 'error' }
				);
			} );
	}

	if ( !mw.user.tokens.exists( 'patrolToken' ) ) {
		// No patrol right, let normal navigation happen.
		return;
	}

	$( function () {
		$( '.patrollink[data-mw="interface"] a' ).on( 'click', function ( e ) {
			patrol( this );
			e.preventDefault();
		} );
	} );
}() );
