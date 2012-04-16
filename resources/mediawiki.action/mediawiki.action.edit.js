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

	function textAreaAutoSaveInit() {
		// If there's no localStorage, there's no point in doing the checks or adding listeners.
		// Also, it should only be run when editing, and not previewing.
		if ( 'localStorage' in window && mw.config.get( 'wgAction' ) == 'edit' ) {
			function saveToLocalStorage ( page, contents ) {
				// the replace call is to remove unicode characters which cannot be saved to localStorage
				localStorage.setItem( 'savedta-' + page.replace( /[^\x00-\x80]/g , '-' ), contents);
			}
			function inLocalStorage ( page ) {
				return localStorage.getItem( 'savedta-' + page.replace( /[^\x00-\x80]/g , '-' ) ) !== null;
			}
			function getLocalStorage ( page ) {
				return localStorage.getItem( 'savedta-' + page.replace( /[^\x00-\x80]/g , '-' ) );
			}
			function removeFromLocalStorage ( page ) {
				return localStorage.removeItem( 'savedta-' + page.replace( /[^\x00-\x80]/g , '-' ) );
			}
			var textArea = document.getElementById( 'wpTextbox1' );
			textArea.addEventListener( 'keyup', function () {
				var value = textArea.value;
				saveToLocalStorage( mw.config.get( 'wgPageName' ), value);
			});
			var saveButton = document.getElementById( 'wpSave' );
			saveButton.addEventListener( 'click', function () {
				removeFromLocalStorage( mw.config.get( 'wgPageName' ) );
			});
			if ( inLocalStorage ( mw.config.get( 'wgPageName' ) ) ) {
				var options = ' <a href="#" id="textarea-replace">' + mw.msg('textarea-replace') + '</a> · <a href="#" id="textarea-discard">' + mw.msg('textarea-discard') + '</a>';
				mw.util.jsMessage( mw.msg( 'textarea-savedversion' ) + options );
				document.getElementById('textarea-replace').addEventListener('click', function (e) {
					textArea.value = getLocalStorage( mw.config.get( 'wgPageName' ) );
					removeFromLocalStorage( mw.config.get( 'wgPageName' ) );
					$( '#mw-js-message' ).slideUp();
					e.preventDefault();
					return false;
				});
				document.getElementById('textarea-discard').addEventListener('click', function (e) {
					removeFromLocalStorage( mw.config.get( 'wgPageName' ) );
					$( '#mw-js-message' ).slideUp();
					e.preventDefault();
					return false;
				});
			}
		}
	}

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
		textAreaAutoSaveInit();
	});

}( jQuery, mediaWiki ) );
