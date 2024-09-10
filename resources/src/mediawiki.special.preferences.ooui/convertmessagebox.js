/**
 * JavaScript for Special:Preferences: Check for successbox to replace with notifications.
 */
( function () {
	$( () => {
		const convertmessagebox = require( 'mediawiki.notification.convertmessagebox' );
		convertmessagebox();
	} );
}() );
