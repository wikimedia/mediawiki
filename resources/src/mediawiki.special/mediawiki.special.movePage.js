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

( function ( $, mw ) {
	$( '#wpNewTitleMain input' ).on( 'blur', function () {
		var newTitle = $( '#wpNewTitleMain input' ).val(),
			nsIds = mw.config.get( 'wgNamespaceIds' ),
			possibleNs, possibleNsLower, messageDialog, windowManager;

		if ( newTitle.indexOf( ':' ) >= 0 ) {
			possibleNs = newTitle.split( ':' )[ 0 ];
			possibleNsLower = possibleNs.toLowerCase();

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
