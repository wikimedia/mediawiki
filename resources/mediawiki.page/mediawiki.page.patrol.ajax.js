/**
 * Animate patrol links to use asynchronous API requests to
 * patrol pages, rather than navigating to a different URI.
 *
 * @author: Marius Hoch hoo@online.de
 */
( function ( mw, $ ) {
	$( document ).ready( function () {
		var $patrolLinks = $( '.patrollink a' );
		if ($patrolLinks.length === 0) {
			return;
		}
		$patrolLinks.on( 'click', function ( event ) {
			event.preventDefault();
			// Start spinner, temporary hide link
			$patrolLinks.parent().hide();
			var $spinner = $.createSpinner( { size: 'small', type: 'inline' } );
			$patrolLinks.parent().parent().append( $spinner );
			var href = $(this).attr( 'href' ),
			rcid = mw.util.getParamValue( 'rcid', href),
			apiRequest = new mw.Api();

			apiRequest.post( {
				action : 'patrol',
				token : mw.user.tokens.get( 'patrolToken' ),
				rcid : rcid
			} )
			.done( function ( data ) {
				$( '.mw-spinner' ).remove();
				if ( data.patrol !== undefined ) {
					// Success
					mw.notify( mw.msg( 'markedaspatrolled' ) );
				} else {
					// Something went wrong
					mw.notify( mw.msg( 'markedaspatrollederror' ) );
				}
			} ).fail( function ( error ) {
				$( '.mw-spinner' ).remove();
				// Show the old patrol link again, to show the user,
				// that he/ she can try it again
				$patrolLinks.parent().show();
				if( error === 'noautopatrol' ) {
					// Can't patrol own
					mw.notify( mw.msg( 'markedaspatrollederror-noautopatrol' ) );
				} else {
					mw.notify( mw.msg( 'markedaspatrollederror' ) );
				}
			} );
		} );
	} );
}( mediaWiki, jQuery ) );
