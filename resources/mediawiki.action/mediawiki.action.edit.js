( function ( $, mw ) {
	var isReady, toolbar, currentFocused;

	isReady = false;

	toolbar = {
		$toolbar: false,
		buttons: [],
		/**
		 * If you want to add buttons, use
		 * mw.toolbar.addButton( imageFile, speedTip, tagOpen, tagClose, sampleText, imageId, selectText );
		 */
		addButton: function () {
			if ( isReady ) {
				toolbar.insertButton.apply( toolbar, arguments );
			} else {
				toolbar.buttons.push( [].slice.call( arguments ) );
			}	
		},
		insertButton: function ( imageFile, speedTip, tagOpen, tagClose, sampleText, imageId, selectText ) {
			var image = $('<img>', {
				width : 23,
				height: 22,
				src   : imageFile,
				alt   : speedTip,
				title : speedTip,
				id    : imageId || '',
				'class': 'mw-toolbar-editbutton'
			} ).click( function () {
				mw.toolbar.insertTags( tagOpen, tagClose, sampleText, selectText );
				return false;
			} );

			toolbar.$toolbar.append( image );
			return true;
		},

		/**
		 * apply tagOpen/tagClose to selection in textarea,
		 * use sampleText instead of selection if there is none.
		 */
		insertTags: function ( tagOpen, tagClose, sampleText, selectText ) {
			if ( currentFocused && currentFocused.length ) {
				currentFocused.textSelection(
					'encapsulateSelection', {
						'pre': tagOpen,
						'peri': sampleText,
						'post': tagClose
					}
				);
			}
		},

		// For backwards compatibility
		init: function () {}
	};

	// Legacy (for compatibility with the code previously in skins/common.edit.js)
	window.addButton = toolbar.addButton;
	window.insertTags = toolbar.insertTags;

	// Explose publicly
	mw.toolbar = toolbar;

	$( document ).ready( function () {
		var buttons, i, c, iframe;

		// currentFocus is used to determine where to insert tags
		currentFocused = $( '#wpTextbox1' );

		// Populate the selector cache for $toolbar 
		toolbar.$toolbar = $( '#toolbar' );

		// Legacy: Merge buttons from mwCustomEditButtons
		buttons = [].concat( toolbar.buttons, window.mwCustomEditButtons );
		for ( i = 0; i < buttons.length; i++ ) {
			if ( $.isArray( buttons[i] ) ) {
				// Passes our button array as arguments
				toolbar.insertButton.apply( toolbar, buttons[i] );
			} else {
				// Legacy mwCustomEditButtons is an object
				c = buttons[i];
				toolbar.insertButton( c.imageFile, c.speedTip, c.tagOpen, 
					c.tagClose, c.sampleText, c.imageId, c.selectText );
			}
		}

		// This causes further calls to addButton to go to insertion directly
		// instead of to the toolbar.buttons queue.
		// It is important that this is after the one and only loop through
		// the the toolbar.buttons queue
		isReady = true;

		// Make sure edit summary does not exceed byte limit
		$( '#wpSummary' ).byteLimit( 250 );

		/**
		 * Restore the edit box scroll state following a preview operation,
		 * and set up a form submission handler to remember this state
		 */
		( function scrollEditBox() {
			var editBox, scrollTop, $editForm;

			editBox = document.getElementById( 'wpTextbox1' );
			scrollTop = document.getElementById( 'wpScrolltop' );
			$editForm = $( '#editform' );
			if ( $editForm.length && editBox && scrollTop ) {
				if ( scrollTop.value ) {
					editBox.scrollTop = scrollTop.value;
				}
				$editForm.submit( function () {
					scrollTop.value = editBox.scrollTop;
				});
			}
		}() );

		$( 'textarea, input:text' ).focus( function () {
			currentFocused = $(this);
		});

		// HACK: make currentFocused work with the usability iframe
		// With proper focus detection support (HTML 5!) this'll be much cleaner
		iframe = $( '.wikiEditor-ui-text iframe' );
		if ( iframe.length > 0 ) {
			$( iframe.get( 0 ).contentWindow.document )
				// for IE
				.add( iframe.get( 0 ).contentWindow.document.body )
				.focus( function () {
					currentFocused = iframe;
				} );
		}
	});

}( jQuery, mediaWiki ) );
