/**
 * Animate patrol links to use asynchronous API requests to
 * patrol pages, rather than navigating to a different URI.
 * 
 * @author: Marius Hoch hoo@online.de
 */
( function ( $, mw ) {
	var $patrolLinks = $( '.patrollink a' );
	if ($patrolLinks.length === 0) {
		return;
	}
	$patrolLinks.on('click', function ( event ) {
		event.preventDefault();
		// Start spinner
		var $spinner = $.createSpinner( { size: 'small', type: 'inline' } );
		$patrolLinks.html( $spinner );
		var href = $(this).attr( 'href' ),
		rcid = mw.util.getParamValue( 'rcid', href),
		apiRequest = new mw.Api();
		
		apiRequest.post({
			action : 'patrol',
			token : mw.user.tokens.get( 'patrolToken' ),
			rcid : rcid
		})
		.done( function ( data ) {
			if ( typeof data.patrol !== 'undefined' ) {
				// Success
				$patrolLinks.removeClass( 'mw-patrol-error' ).
					addClass( 'mw-patrol-success' );
				$patrolLinks.html( mw.msg( 'markedaspatrolled' ) );
			}else{
				$patrolLinks.addClass( 'mw-patrol-error' );
				// Something went wrong
				$patrolLinks.html( mw.msg( 'markedaspatrollederror' ) );
			}
		} ).fail( function ( error ) {
			$patrolLinks.addClass( 'mw-patrol-error' );
			if( error === 'noautopatrol' ) {
				// Can't patrol own
				$patrolLinks.html( mw.msg( 'markedaspatrollederror-noautopatrol' ) );
			}else{
				$patrolLinks.html( mw.msg( 'markedaspatrollederror' ) );
			}
		} );
	});
}( jQuery, mediaWiki ) );
