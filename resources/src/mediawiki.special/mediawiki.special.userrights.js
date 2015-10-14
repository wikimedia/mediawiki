/*!
 * JavaScript for Special:Preferences
 */
( function ( mw, $ ) {
	// Check for messageboxes (.successbox, .warningbox, .errorbox) to replace with notifications
	mw.notification.convertsuccessbox( {
		$msgBoxEl: $( '.successbox' ),
		$closeEl: $( '#mw-content-text' )
	} );
} )( mediaWiki, jQuery );
