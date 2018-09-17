/*!
 * JavaScript for Special:Preferences: Check for successbox to replace with notifications.
 */
( function () {
	$( function () {
		var convertmessagebox = require( 'mediawiki.notification.convertmessagebox' );
		convertmessagebox();
	} );
}() );
