/**
 * Interface for the classic edit toolbar.
 *
 * @class mw.toolbar
 * @singleton
 */
( function ( mw, $ ) {
	var toolbar, isReady, $toolbar, queue, slice, $currentFocused,
		conf = mw.config.get( [
			'wgDBname',
			'wgAction',
			'wgPageName'
		] );

	/**
	 * Internal helper that does the actual insertion of the button into the toolbar.
	 *
	 * See #addButton for parameter documentation.
	 *
	 * @private
	 */
	function insertButton( b, speedTip, tagOpen, tagClose, sampleText, imageId ) {
		// Backwards compatibility
		if ( typeof b !== 'object' ) {
			b = {
				imageFile: b,
				speedTip: speedTip,
				tagOpen: tagOpen,
				tagClose: tagClose,
				sampleText: sampleText,
				imageId: imageId
			};
		}
		var $image = $( '<img>' ).attr( {
			width: 23,
			height: 22,
			src: b.imageFile,
			alt: b.speedTip,
			title: b.speedTip,
			id: b.imageId || undefined,
			'class': 'mw-toolbar-editbutton'
		} ).click( function () {
			toolbar.insertTags( b.tagOpen, b.tagClose, b.sampleText );
			return false;
		} );

		$toolbar.append( $image );
	}

	isReady = false;
	$toolbar = false;
	/**
	 * @private
	 * @property {Array}
	 * Contains button objects (and for backwards compatibilty, it can
	 * also contains an arguments array for insertButton).
	 */
	queue = [];
	slice = queue.slice;

	toolbar = {

		/**
		 * Add buttons to the toolbar.
		 *
		 * Takes care of race conditions and time-based dependencies
		 * by placing buttons in a queue if this method is called before
		 * the toolbar is created.
		 *
		 * For compatiblity, passing the properties listed below as separate arguments
		 * (in the listed order) is also supported.
		 *
		 * @param {Object} button Object with the following properties:
		 * @param {string} button.imageFile
		 * @param {string} button.speedTip
		 * @param {string} button.tagOpen
		 * @param {string} button.tagClose
		 * @param {string} button.sampleText
		 * @param {string} [button.imageId]
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
		 * Example usage:
		 *     addButtons( [ { .. }, { .. }, { .. } ] );
		 *     addButtons( { .. }, { .. } );
		 *
		 * @param {Object|Array} [buttons...] An array of button objects or the first
		 *  button object in a list of variadic arguments.
		 */
		addButtons: function ( buttons ) {
			if ( !$.isArray( buttons ) ) {
				buttons = slice.call( arguments );
			}
			if ( isReady ) {
				$.each( buttons, function () {
					insertButton( this );
				} );
			} else {
				// Push each button into the queue
				queue.push.apply( queue, buttons );
			}
		},

		/**
		 * Apply tagOpen/tagClose to selection in currently focused textarea.
		 *
		 * Uses `sampleText` if selection is empty.
		 *
		 * @param {string} tagOpen
		 * @param {string} tagClose
		 * @param {string} sampleText
		 */
		insertTags: function ( tagOpen, tagClose, sampleText ) {
			if ( $currentFocused && $currentFocused.length ) {
				$currentFocused.textSelection(
					'encapsulateSelection', {
						pre: tagOpen,
						peri: sampleText,
						post: tagClose
					}
				);
			}
		},

		// For backwards compatibility,
		// Called from EditPage.php, maybe in other places as well.
		init: function () {}
	};

	// Legacy (for compatibility with the code previously in skins/common.edit.js)
	mw.log.deprecate( window, 'addButton', toolbar.addButton, 'Use mw.toolbar.addButton instead.' );
	mw.log.deprecate( window, 'insertTags', toolbar.insertTags, 'Use mw.toolbar.insertTags instead.' );

	// Expose API publicly
	mw.toolbar = toolbar;

	function saveToLocalStorage( page, contents ) {
		localStorage.setItem( conf.wgDBname + '-savedta-' + page, contents );
	}

	function getLocalStorage( page ) {
		return localStorage.getItem( conf.wgDBname + '-savedta-' + page );
	}

	function removeFromLocalStorage( page ) {
		return localStorage.removeItem( conf.wgDBname + '-savedta-' + page );
	}

	function textAreaAutoSaveInit() {
		// If there's no localStorage, there's no point in doing the checks or adding listeners.
		// Also, it should only be run when editing, and not previewing.
		if ( 'localStorage' in window && conf.wgAction === 'edit' ) {
			var pageName = conf.wgPageName,
				$textArea = $( '#wpTextbox1' ),
				node = $( '<span>' ),
				notif,
				$replaceLink,
				$discardLink;

			$textArea.change( function () {
				saveToLocalStorage( pageName, $textArea.val() );
			} );

			$( '#wpSave' ).click( function () {
				removeFromLocalStorage( pageName );
			} );

			if ( $.trim( getLocalStorage( pageName ) ) !== $.trim( $textArea.val() ) ) {
				$replaceLink = $( '<a>' )
					.text( mw.msg( 'textarea-use-draft' ) )
					.click( function ( e ) {
						$textArea.val( getLocalStorage( pageName ) );
						e.preventDefault();
						notif.close();
					} );

				$discardLink = $( '<a>' )
					.text( mw.msg( 'textarea-use-current-version' ) )
					.click( function ( e ) {
						removeFromLocalStorage( pageName );
						e.preventDefault();
						notif.close();
					} );

				node.append(
					$( '<span>' ).text( mw.msg( 'textarea-draft-found' ) ),
					$( '<br>' ),
					$replaceLink,
					document.createTextNode( ' · ' ),
					$discardLink
				);
				notif = mw.notification.notify( node, { autoHide: false } );
			}
		}
	}

	$( function () {
		var i, b, editBox, scrollTop, $editForm;

		// Used to determine where to insert tags
		$currentFocused = $( '#wpTextbox1' );

		// Populate the selector cache for $toolbar
		$toolbar = $( '#toolbar' );

		for ( i = 0; i < queue.length; i++ ) {
			b = queue[i];
			if ( $.isArray( b ) ) {
				// Forwarded arguments array from mw.toolbar.addButton
				insertButton.apply( toolbar, b );
			} else {
				// Raw object from mw.toolbar.addButtons
				insertButton( b );
			}
		}

		// Clear queue
		queue.length = 0;

		// This causes further calls to addButton to go to insertion directly
		// instead of to the queue.
		// It is important that this is after the one and only loop through
		// the the queue
		isReady = true;

		// Make sure edit summary does not exceed byte limit
		$( '#wpSummary' ).byteLimit( 255 );

		// Restore the edit box scroll state following a preview operation,
		// and set up a form submission handler to remember this state.
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

		// Apply to dynamically created textboxes as well as normal ones
		$( document ).on( 'focus', 'textarea, input:text', function () {
			$currentFocused = $( this );
		} );

		textAreaAutoSaveInit();
	});

}( mediaWiki, jQuery ) );
