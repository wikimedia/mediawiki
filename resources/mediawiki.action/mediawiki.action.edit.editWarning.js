/*
 * Javascript for module editWarning
 */
( function ( mw, $ ) {
	$( function () {
		// Check if EditWarning is enabled and if we need it
		if ( $( '#wpTextbox1' ).length === 0 ) {
			return true;
		}
		// Get the original values of some form elements
		$( '#wpTextbox1, #wpSummary' ).each( function () {
			$( this ).data( 'origtext', $( this ).val() );
		});
		var savedWindowOnBeforeUnload;
		$( window )
			.on( 'beforeunload.editwarning', function () {
				var retval;

				// Check if the current values of some form elements are the same as
				// the original values
				if (
					mw.config.get( 'wgAction' ) === 'submit' ||
						$( '#wpTextbox1' ).data( 'origtext' ) !== $( '#wpTextbox1' ).val() ||
						$( '#wpSummary' ).data( 'origtext' ) !== $( '#wpSummary' ).val()
				) {
					// Return our message
					retval = mw.msg( 'editwarning-warning' );
				}

				// Unset the onbeforeunload handler so we don't break page caching in Firefox
				savedWindowOnBeforeUnload = window.onbeforeunload;
				window.onbeforeunload = null;
				if ( retval !== undefined ) {
					// ...but if the user chooses not to leave the page, we need to rebind it
					setTimeout( function () {
						window.onbeforeunload = savedWindowOnBeforeUnload;
					}, 1 );
					return retval;
				}
			} )
			.on( 'pageshow.editwarning', function () {
				// Re-add onbeforeunload handler
				if ( !window.onbeforeunload ) {
					window.onbeforeunload = savedWindowOnBeforeUnload;
				}
			} );

		// Add form submission handler
		$( '#editform' ).submit( function () {
			// Unbind our handlers
			$( window ).off( '.editwarning' );
		} );
	} );

}( mediaWiki, jQuery ) );

