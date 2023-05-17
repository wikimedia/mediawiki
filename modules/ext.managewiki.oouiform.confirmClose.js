/*!
 * JavaScript for Special:ManageWiki: Enable save button and prevent the window being accidentally
 * closed when any form field is changed.
 */
( function () {
	$( function () {
		var allowCloseWindow, saveButton;

		if ( !( $( '#managewiki-submit' ).length > 0 ) ) {
			return;
		}

		// Check if all of the form values are unchanged.
		// (This function could be changed to infuse and check OOUI widgets, but that would only make it
		// slower and more complicated. It works fine to treat them as HTML elements.)
		function isManageWikiChanged() {
			var $fields, i;

			$fields = $( '#managewiki-form  .mw-htmlform-cloner-ul' );
			for ( i = 0; i < $fields.length; i++ ) {
				if ( Number( $fields[ i ].dataset.initialFieldSize ) !== $fields[ i ].children.length ) {
					return true;
				}
			}

			$fields = $( '#managewiki-form :input[name]:not( #managewiki-submit-reason :input[name] )' );
			for ( i = 0; i < $fields.length; i++ ) {
				if ( $fields[ i ].defaultChecked !== undefined && $fields[ i ].type === 'checkbox' && $fields[ i ].defaultChecked !== $fields[ i ].checked ) {
					return true;
				} else if ( $fields[ i ].defaultValue !== undefined && $fields[ i ].defaultValue !== $fields[ i ].value ) {
					return true;
				}
			}

			return false;
		}

		// Store the initial number of children of cloners for later use, as an equivalent of
		// defaultValue.
		$( '#managewiki-form .mw-htmlform-cloner-ul' ).each( function () {
			if ( this.dataset.initialFieldSize === undefined ) {
				this.dataset.initialFieldSize = this.children.length;
			}
		} );

		saveButton = OO.ui.infuse( $( '#managewiki-submit' ) );

		// Disable the save button unless settings have changed
		// Check if settings have been changed before JS has finished loading
		saveButton.setDisabled( !isManageWikiChanged() );

		// Attach capturing event handlers to the document, to catch events inside OOUI dropdowns:
		// * Use capture because OO.ui.SelectWidget also does, and it stops event propagation,
		//   so the event is not fired on descendant elements
		// * Attach to the document because the dropdowns are in the .oo-ui-defaultOverlay element
		//   (and it doesn't exist yet at this point, so we can't attach them to it)
		[ 'change', 'keyup', 'mouseup' ].forEach( function ( eventType ) {
			document.addEventListener( eventType, function () {
				// Make sure SelectWidget's event handlers run first
				setTimeout( function () {
					saveButton.setDisabled( !isManageWikiChanged() );
				} );
			}, true );
		} );

		// Set up a message to notify users if they try to leave the page without
		// saving.
		allowCloseWindow = mw.confirmCloseWindow( {
			test: isManageWikiChanged,
			message: mw.msg( 'managewiki-warning-changes', mw.msg( 'managewiki-save' ) )
		} );

		$( '#managewiki-form' ).on( 'submit', allowCloseWindow.release );
	} );
}() );
