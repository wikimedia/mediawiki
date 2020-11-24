/*!
 * JavaScript for Special:UserRights
 */
( function () {
	var convertmessagebox = require( 'mediawiki.notification.convertmessagebox' ),
		$wpReason = $( '#wpReason' );

	// Replace successbox with notifications
	convertmessagebox();

	// Dynamically show/hide the "other time" input under each dropdown
	$( '.mw-userrights-nested select' ).on( 'change', function ( e ) {
		$( e.target.parentNode ).find( 'input' ).toggle( $( e.target ).val() === 'other' );
	} );

	$wpReason.codePointLimit( mw.config.get( 'wgCommentCodePointLimit' ) );

}() );
