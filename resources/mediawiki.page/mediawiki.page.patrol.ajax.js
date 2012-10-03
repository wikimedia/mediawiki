/**
 * Animate patrol links to use asynchronous API requests to
 * patrol pages, rather than navigating to a different URI.
 *
 * @author: Marius Hoch hoo@online.de
 */
( function ( mw, $ ) {
	$( document ).ready( function () {
		var $patrolLinks = $( '.patrollink a' );
		if ( $patrolLinks.length === 0 ) {
			return;
		}
		$patrolLinks.on( 'click', function ( event ) {
			var $spinner, href, rcid, apiRequest;
			event.preventDefault();
			// Start spinner, temporary hide link
			$patrolLinks.hide();
			$spinner = $.createSpinner( {
				size: 'small',
				type: 'inline'
			} )
			.addClass( 'mw-patrol-spinner' );
			$patrolLinks.after( $spinner );
			href = $(this).attr( 'href' );
			rcid = mw.util.getParamValue( 'rcid', href );
			apiRequest = new mw.Api();

			apiRequest.post( {
				action : 'patrol',
				token : mw.user.tokens.get( 'patrolToken' ),
				rcid : rcid
			} )
			.done( function ( data ) {
				// Remove the spinner and brackets
				$patrolLinks.closest( '.patrollink' ).remove();
				if ( data.patrol !== undefined ) {
					// Success
					var title = new mw.Title( data.patrol.title, data.patrol.ns );
					mw.notify( mw.msg( 'markedaspatrollednotify', title.toText() ) );
				} else {
					// Something went wrong
					mw.notify( mw.msg( 'markedaspatrollederrornotify' ) );
				}
			} )
			.fail( function ( error ) {
				$( '.mw-patrol-spinner' ).remove();
				// Show the old patrol link again, to show the user,
				// that he/ she can try it again
				$patrolLinks.show();
				if( error === 'noautopatrol' ) {
					// Can't patrol own
					mw.notify( mw.msg( 'markedaspatrollederror-noautopatrol' ) );
				} else {
					mw.notify( mw.msg( 'markedaspatrollederrornotify' ) );
				}
			} );
		} );
	} );
}( mediaWiki, jQuery ) );
