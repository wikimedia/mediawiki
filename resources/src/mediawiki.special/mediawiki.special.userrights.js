/*!
 * JavaScript for Special:Preferences
 */
jQuery( function ( $ ) {
	// Check for messageboxes (.successbox, .warningbox, .errorbox) to replace with notifications
	mw.searchboxToNotify( {
		messageBoxSelector: '.successbox',
		closeSelector: '#mw-content-text'
	} );
} );
