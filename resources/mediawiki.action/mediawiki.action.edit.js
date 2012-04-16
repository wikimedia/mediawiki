( function ( mw, $ ) {
	var isReady, toolbar, currentFocused, queue, $toolbar, slice;

	isReady = false;
	queue = [];
	$toolbar = false;
	slice = Array.prototype.slice;

	/**
	 * Internal helper that does the actual insertion
	 * of the button into the toolbar.
	 * See mw.toolbar.addButton for parameter documentation.
	 */
	function insertButton( b /* imageFile */, speedTip, tagOpen, tagClose, sampleText, imageId, selectText ) {
		// Backwards compatibility
		if ( typeof b !== 'object' ) {
			b = {
				imageFile: b,
				speedTip: speedTip,
				tagOpen: tagOpen,
				tagClose: tagClose,
				sampleText: sampleText,
				imageId: imageId,
				selectText: selectText
			};
		}
		var $image = $( '<img>', {
			width : 23,
			height: 22,
			src   : b.imageFile,
			alt   : b.speedTip,
			title : b.speedTip,
			id    : b.imageId || undefined,
			'class': 'mw-toolbar-editbutton'
		} ).click( function () {
			toolbar.insertTags( b.tagOpen, b.tagClose, b.sampleText, b.selectText );
			return false;
		} );

		$toolbar.append( $image );
		return true;
	}

	toolbar = {
		/**
		 * Add buttons to the toolbar.
		 * Takes care of race conditions and time-based dependencies
		 * by placing buttons in a queue if this method is called before
		 * the toolbar is created.
		 * @param {Object} button: Object with the following properties:
		 * - imageFile
		 * - speedTip
		 * - tagOpen
		 * - tagClose
		 * - sampleText
		 * - imageId
		 * - selectText
		 * For compatiblity, passing the above as separate arguments
		 * (in the listed order) is also supported.
		 */
		addButton: function () {
			if ( isReady ) {
				insertButton.apply( toolbar, arguments );
			} else {
				// Convert arguments list to array
				queue.push( slice.call( arguments ) );
			}
		},

		/**
		 * Apply tagOpen/tagClose to selection in textarea,
		 * use sampleText instead of selection if there is none.
		 */
		insertTags: function ( tagOpen, tagClose, sampleText ) {
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

		// For backwards compatibility,
		// Called from EditPage.php, maybe in other places as well.
		init: function () {}
	};

	// Legacy (for compatibility with the code previously in skins/common.edit.js)
	window.addButton = toolbar.addButton;
	window.insertTags = toolbar.insertTags;

	// Explose API publicly
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
		var buttons, i, b, $iframe;

		// currentFocus is used to determine where to insert tags
		currentFocused = $( '#wpTextbox1' );

		// Populate the selector cache for $toolbar
		$toolbar = $( '#toolbar' );

		// Legacy: Merge buttons from mwCustomEditButtons
		buttons = [].concat( queue, window.mwCustomEditButtons );
		// Clear queue
		queue.length = 0;
		for ( i = 0; i < buttons.length; i++ ) {
			b = buttons[i];
			if ( $.isArray( b ) ) {
				// Forwarded arguments array from mw.toolbar.addButton
				insertButton.apply( toolbar, b );
			} else {
				// Raw object from legacy mwCustomEditButtons
				insertButton( b );
			}
		}

		// This causes further calls to addButton to go to insertion directly
		// instead of to the toolbar.buttons queue.
		// It is important that this is after the one and only loop through
		// the the toolbar.buttons queue
		isReady = true;

		// Make sure edit summary does not exceed byte limit
		$( '#wpSummary' ).byteLimit( 255 );

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
		// TODO: Get rid of this WikiEditor code from MediaWiki core!
		$iframe = $( '.wikiEditor-ui-text iframe' );
		if ( $iframe.length > 0 ) {
			$( $iframe.get( 0 ).contentWindow.document )
				// for IE
				.add( $iframe.get( 0 ).contentWindow.document.body )
				.focus( function () {
					currentFocused = $iframe;
				} );
		}
		textAreaAutoSaveInit();
	});

}( mediaWiki, jQuery ) );
