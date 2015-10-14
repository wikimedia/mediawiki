/*!
 * JavaScript for Special:Preferences
 */
( function ( mw ) {
	// Check for messageboxes (.successbox, .warningbox, .errorbox) to replace with notifications
	mw.notification.convertsuccessbox();
} )( mediaWiki, jQuery );
