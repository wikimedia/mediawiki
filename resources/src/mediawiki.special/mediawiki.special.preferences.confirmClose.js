/*!
 * JavaScript for Special:Preferences: Enable save button and prevent the window being accidentally
 * closed when any form field is changed.
 */
( function ( mw, $ ) {
	$( function () {
		var allowCloseWindow;

		// Check if all of the form values are unchanged
		function isPrefsChanged() {
			var inputs = $( '#mw-prefs-form :input[name]' ),
				input, $input, inputType,
				index, optIndex,
				opt;

			for ( index = 0; index < inputs.length; index++ ) {
				input = inputs[ index ];
				$input = $( input );

				// Different types of inputs have different methods for accessing defaults
				if ( $input.is( 'select' ) ) {
					// <select> has the property defaultSelected for each option
					for ( optIndex = 0; optIndex < input.options.length; optIndex++ ) {
						opt = input.options[ optIndex ];
						if ( opt.selected !== opt.defaultSelected ) {
							return true;
						}
					}
				} else if ( $input.is( 'input' ) || $input.is( 'textarea' ) ) {
					// <input> has defaultValue or defaultChecked
					inputType = input.type;
					if ( inputType === 'radio' || inputType === 'checkbox' ) {
						if ( input.checked !== input.defaultChecked ) {
							return true;
						}
					} else if ( input.value !== input.defaultValue ) {
						return true;
					}
				}
			}

			return false;
		}

		// Disable the button to save preferences unless preferences have changed
		// Check if preferences have been changed before JS has finished loading
		$( '#prefcontrol' ).prop( 'disabled', !isPrefsChanged() );
		$( '#preferences > fieldset' ).on( 'change keyup mouseup', function () {
			$( '#prefcontrol' ).prop( 'disabled', !isPrefsChanged() );
		} );

		// Set up a message to notify users if they try to leave the page without
		// saving.
		allowCloseWindow = mw.confirmCloseWindow( {
			test: isPrefsChanged,
			message: mw.msg( 'prefswarning-warning', mw.msg( 'saveprefs' ) ),
			namespace: 'prefswarning'
		} );
		$( '#mw-prefs-form' ).submit( $.proxy( allowCloseWindow, 'release' ) );
		$( '#mw-prefs-restoreprefs' ).click( $.proxy( allowCloseWindow, 'release' ) );
	} );
}( mediaWiki, jQuery ) );
