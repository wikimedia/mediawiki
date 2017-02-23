/*!
 * JavaScript for Special:MovePage
 */
jQuery( function ( $ ) {
	// Infuse for pretty dropdown
	OO.ui.infuse( 'wpNewTitle' );
	// Limit to 255 bytes, not characters
	OO.ui.infuse( 'wpReason' ).$input.byteLimit();
	// Infuse for nicer "help" popup
	if ( $( '#wpMovetalk-field' ).length ) {
		OO.ui.infuse( 'wpMovetalk-field' );
	}
} );

/*
 * Warning message for possible repeated namespace it the new title
 */
( function ( $, mw ) {
	$( '#wpNewTitleMain input' ).on( 'blur', function () {
		var newTitle = $( '#wpNewTitleMain input' ).val(),
			// Namespace IDs
			nsIds = mw.config.get( 'wgNamespaceIds' ),
			possibleNs, possibleNsLower, messageDialog, windowManager;

		// Check whether the new title contains character ':'
		if ( newTitle.indexOf( ':' ) >= 0 ) {
			// Possible namespace in the new title input
			possibleNs = newTitle.split( ':' )[ 0 ];
			possibleNsLower = possibleNs.toLowerCase();

			// Check whether the possible namespace string is in namespaces
			if ( Object.keys( nsIds ).indexOf( possibleNsLower ) >= 0 ) {
				messageDialog = new OO.ui.MessageDialog();

				// Create and append a window manager.
				windowManager = new OO.ui.WindowManager();
				$( 'body' ).append( windowManager.$element );

				// Add the dialog to the window manager.
				windowManager.addWindows( [ messageDialog ] );

				// Configure the message dialog when it is opened with the window manager's openWindow() method.
				windowManager.openWindow( messageDialog, {
					title: 'Repeated namespace in the title',
					message: 'Please verify your new page title: ' + possibleNs + ':' + newTitle + '. ' + possibleNs + ':' + possibleNs + ':\' in a page title is usually a mistake.',
					actions: [
						{
							action: 'accept',
							label: 'OK',
							flags: 'primary'
						}
					]
				} );
			}
		}
	} );
}( jQuery, mediaWiki ) );
