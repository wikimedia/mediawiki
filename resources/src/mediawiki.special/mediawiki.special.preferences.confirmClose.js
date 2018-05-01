/*!
 * JavaScript for Special:Preferences: Enable save button and prevent the window being accidentally
 * closed when any form field is changed.
 */
( function ( mw, $ ) {
	$( function () {
		var allowCloseWindow, saveButton, restoreButton,
			oouiEnabled = $( '#mw-prefs-form' ).hasClass( 'mw-htmlform-ooui' );

		// Check if all of the form values are unchanged.
		// (This function could be changed to infuse and check OOUI widgets, but that would only make it
		// slower and more complicated. It works fine to treat them as HTML elements.)
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

		if ( oouiEnabled ) {
			saveButton = OO.ui.infuse( $( '#prefcontrol' ) );
			restoreButton = OO.ui.infuse( $( '#mw-prefs-restoreprefs' ) );

			// Disable the button to save preferences unless preferences have changed
			// Check if preferences have been changed before JS has finished loading
			saveButton.setDisabled( !isPrefsChanged() );
			$( '#preferences .oo-ui-fieldsetLayout' ).on( 'change keyup mouseup', function () {
				saveButton.setDisabled( !isPrefsChanged() );
			} );
		} else {
			// Disable the button to save preferences unless preferences have changed
			// Check if preferences have been changed before JS has finished loading
			$( '#prefcontrol' ).prop( 'disabled', !isPrefsChanged() );
			$( '#preferences > fieldset' ).on( 'change keyup mouseup', function () {
				$( '#prefcontrol' ).prop( 'disabled', !isPrefsChanged() );
			} );
		}

		// Set up a message to notify users if they try to leave the page without
		// saving.
		allowCloseWindow = mw.confirmCloseWindow( {
			test: isPrefsChanged,
			message: mw.msg( 'prefswarning-warning', mw.msg( 'saveprefs' ) ),
			namespace: 'prefswarning'
		} );
		$( '#mw-prefs-form' ).on( 'submit', $.proxy( allowCloseWindow, 'release' ) );
		if ( oouiEnabled ) {
			restoreButton.on( 'click', function () {
				allowCloseWindow.release();
				// The default behavior of events in OOUI is always prevented. Follow the link manually.
				// Note that middle-click etc. still works, as it doesn't emit a OOUI 'click' event.
				location.href = restoreButton.getHref();
			} );
		} else {
			$( '#mw-prefs-restoreprefs' ).on( 'click', $.proxy( allowCloseWindow, 'release' ) );
		}
	} );
}( mediaWiki, jQuery ) );
