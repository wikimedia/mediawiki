/*!
 * JavaScript for Special:UserRights
 */
$( () => {
	// Replace successbox with notifications
	require( 'mediawiki.notification.convertmessagebox' )();

	$( '#wpReason' ).codePointLimit( mw.config.get( 'wgCommentCodePointLimit' ) );
} );
