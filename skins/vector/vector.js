/**
 * Vector-specific scripts
 */
jQuery( function ( $ ) {
	$( 'div.vectorMenu' ).each( function () {
		var $el = $( this );
		$el.find( 'h3:first a:first' )
			// For accessibility, show the menu when the hidden link in the menu is clicked (bug 24298)
			.click( function ( e ) {
				$el.find( '.menu:first' ).toggleClass( 'menuForceShow' );
				e.preventDefault();
			} )
			// When the hidden link has focus, also set a class that will change the arrow icon
			.focus( function () {
				$el.addClass( 'vectorMenuFocus' );
			} )
			.blur( function () {
				$el.removeClass( 'vectorMenuFocus' );
			} );
	} );

/*
 * Edit warning for Vector
 */

	// Check if EditWarning is enabled and if we need it
	if ( $( '#wpTextbox1' ).length === 0 ) {
		return true;
	}
	// Get the original values of some form elements
	$( '#wpTextbox1, #wpSummary' ).each( function () {
		$( this ).data( 'origtext', $( this ).val() );
	} );
	var savedWindowOnBeforeUnload;
	$( window )
		.on( 'beforeunload.editwarning', function () {
			var retval, mw;

			// Check if the current values of some form elements are the same as
			// the original values
			if (
				mw.config.get( 'wgAction' ) === 'submit' ||
					$( '#wpTextbox1' ).data( 'origtext' ) !== $( '#wpTextbox1' ).val() ||
					$( '#wpSummary' ).data( 'origtext' ) !== $( '#wpSummary' ).val()
			) {
				// Return our message
				retval = mw.msg( 'vector-editwarning-warning' );
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
			if ( window.onbeforeunload === null ) {
				window.onbeforeunload = savedWindowOnBeforeUnload;
			}
		} );

	// Add form submission handler
	$( '#editform' ).submit( function () {
		// Unbind our handlers
		$( window ).off( '.editwarning' );
	} );
} );

