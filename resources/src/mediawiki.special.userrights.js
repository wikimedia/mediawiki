/*!
 * JavaScript for Special:UserRights
 */
$( () => {
	// Replace successbox with notifications
	require( 'mediawiki.notification.convertmessagebox' )();

	$( '#wpReason > input' ).codePointLimit( mw.config.get( 'wgCommentCodePointLimit' ) );
} );
