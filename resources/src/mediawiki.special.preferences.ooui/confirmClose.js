/*!
 * JavaScript for Special:Preferences: Enable save button and prevent the window being accidentally
 * closed when any form field is changed.
 */
$( () => {
	// Check if all of the form values are unchanged.
	// (This function could be changed to infuse and check OOUI widgets, but that would only make it
	// slower and more complicated. It works fine to treat them as HTML elements.)
	function isPrefsChanged() {
		// eslint-disable-next-line no-jquery/no-sizzle
		const $inputs = $( '#mw-prefs-form :input[name]' );

		for ( let index = 0; index < $inputs.length; index++ ) {
			const input = $inputs[ index ];
			const $input = $( input );

			// Different types of inputs have different methods for accessing defaults
			if ( $input.is( 'select' ) ) {
				// <select> has the property defaultSelected for each option
				for ( let optIndex = 0; optIndex < input.options.length; optIndex++ ) {
					const opt = input.options[ optIndex ];
					if ( opt.selected !== opt.defaultSelected ) {
						return true;
					}
				}
			} else if ( $input.is( 'input' ) || $input.is( 'textarea' ) ) {
				// <input> has defaultValue or defaultChecked
				const inputType = input.type;
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

	const saveButton = OO.ui.infuse( $( '#prefcontrol' ) );

	// Disable the button to save preferences unless preferences have changed
	// Check if preferences have been changed before JS has finished loading
	saveButton.setDisabled( !isPrefsChanged() );
	// Attach capturing event handlers to the document, to catch events inside OOUI dropdowns:
	// * Use capture because OO.ui.SelectWidget also does, and it stops event propagation,
	//   so the event is not fired on descendant elements
	// * Attach to the document because the dropdowns are in the .oo-ui-defaultOverlay element
	//   (and it doesn't exist yet at this point, so we can't attach them to it)
	[ 'change', 'keyup', 'mouseup' ].forEach( ( eventType ) => {
		document.addEventListener( eventType, () => {
			// Make sure SelectWidget's event handlers run first
			setTimeout( () => {
				saveButton.setDisabled( !isPrefsChanged() );
			} );
		}, true );
	} );

	// Prompt users if they try to leave the page without saving.
	const allowCloseWindow = mw.confirmCloseWindow( {
		test: isPrefsChanged
	} );
	$( '#mw-prefs-form' ).on( 'submit', allowCloseWindow.release );
} );
