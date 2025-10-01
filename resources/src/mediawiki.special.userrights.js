/*!
 * JavaScript for Special:UserRights
 */
$( () => {
	// Replace successbox with notifications
	require( 'mediawiki.notification.convertmessagebox' )();

	// Dynamically show/hide the "other time" input under each dropdown
	$( '.mw-userrights-nested select' ).on( 'change', ( e ) => {
		$( e.target.parentNode ).find( 'input' ).toggle( $( e.target ).val() === 'other' );
	} );

	$( '#wpReason' ).codePointLimit( mw.config.get( 'wgCommentCodePointLimit' ) );
} );
