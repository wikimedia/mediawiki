/**
 * Interface for the classic edit toolbar.
 *
 * @class mw.toolbar
 * @singleton
 */
( function ( mw, $ ) {
	var toolbar, isReady, $toolbar, queue, slice, $currentFocused;

	/**
	 * Internal helper that does the actual insertion of the button into the toolbar.
	 *
	 * See #addButton for parameter documentation.
	 *
	 * @private
	 */
	function insertButton( b, speedTip, tagOpen, tagClose, sampleText, imageId ) {
		var $button;

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

		if ( b.imageFile ) {
			$button = $( '<img>' ).attr( {
			src: b.imageFile,
			alt: b.speedTip,
			title: b.speedTip,
			id: b.imageId || undefined,
			'class': 'mw-toolbar-editbutton'
			} );
		} else {
			$button = $( '<div>' ).attr( {
				title: b.speedTip,
				id: b.imageId || undefined,
				'class': 'mw-toolbar-editbutton'
			} );
		}

		$button.click( function ( e ) {
			if ( b.onClick !== undefined ) {
				b.onClick( e );
			} else {
				toolbar.insertTags( b.tagOpen, b.tagClose, b.sampleText );
			}

			return false;
		} );

		$toolbar.append( $button );
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
		 * For backwards-compatibility, passing `imageFile`, `speedTip`, `tagOpen`, `tagClose`,
		 * `sampleText` and `imageId` as separate arguments (in this order) is also supported.
		 *
		 * @param {Object} button Object with the following properties.
		 *  You are required to provide *either* the `onClick` parameter, or the three parameters
		 *  `tagOpen`, `tagClose` and `sampleText`, but not both (they're mutually exclusive).
		 * @param {string} [button.imageFile] Image to use for the button.
		 * @param {string} button.speedTip Tooltip displayed when user mouses over the button.
		 * @param {Function} [button.onClick] Function to be executed when the button is clicked.
		 * @param {string} [button.tagOpen]
		 * @param {string} [button.tagClose]
		 * @param {string} [button.sampleText] Alternative to `onClick`. `tagOpen`, `tagClose` and
		 *  `sampleText` together provide the markup that should be inserted into page text at
		 *  current cursor position.
		 * @param {string} [button.imageId] `id` attribute of the button HTML element. Can be
         *  used to define the image with CSS if it's not provided as `imageFile`.
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
		 * Add multiple buttons to the toolbar (see also #addButton).
		 *
		 * Example usage:
		 *
		 *     addButtons( [ { .. }, { .. }, { .. } ] );
		 *     addButtons( { .. }, { .. } );
		 *
		 * @param {Object|Array...} [buttons] An array of button objects or the first
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
			} );
		}

		// Apply to dynamically created textboxes as well as normal ones
		$( document ).on( 'focus', 'textarea, input:text', function () {
			$currentFocused = $( this );
		} );
	} );

}( mediaWiki, jQuery ) );
