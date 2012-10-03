/**
 * Animate patrol links to use asynchronous API requests to
 * patrol pages, rather than navigating to a different URI.
 *
 * @author: Marius Hoch hoo@online.de
 */
( function ( mw, $ ) {
	$( document ).ready( function () {
		if ( !mw.user.tokens.exists( 'patrolToken' ) ) {
			// Patrol token isn't present, probably user.tokens is outdated from cache
			return;
		}
		var $patrolLinks = $( '.patrollink a' );
		$patrolLinks.on( 'click', function ( event ) {
			var $spinner, href, rcid, apiRequest;
			event.preventDefault();
			// hide the link and create a spinner to show it inside of the brackets
			$spinner = $.createSpinner( {
				size: 'small',
				type: 'inline'
			} );
			$( this ).hide().after( $spinner );

			href = $( this ).attr( 'href' );
			rcid = mw.util.getParamValue( 'rcid', href );
			apiRequest = new mw.Api();

			apiRequest.post( {
				action: 'patrol',
				token: mw.user.tokens.get( 'patrolToken' ),
				rcid: rcid
			} )
			.done( function ( data ) {
				// Remove the spinner and brackets (removes ALL patrollinks on page)
				$patrolLinks.closest( '.patrollink' ).remove();
				if ( data.patrol !== undefined ) {
					// Success
					var title = new mw.Title( data.patrol.title, data.patrol.ns );
					mw.notify( mw.msg( 'markedaspatrollednotify', title.toText() ) );
				} else {
					// This should never happen as errors should go to .fail
					mw.notify( mw.msg( 'markedaspatrollederrornotify' ) );
				}
			} )
			.fail( function ( error ) {
				$spinner.remove();
				// Show the old patrol link again, to show the user,
				// that he/ she can try it again
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
