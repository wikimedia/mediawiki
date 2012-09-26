( function ( mw, $ ) {
	var isReady, toolbar, $currentFocused, queue, $toolbar, slice;

	isReady = false;
	queue = [];
	$toolbar = false;
	slice = Array.prototype.slice;

	/**
	 * Internal helper that does the actual insertion
	 * of the buttons into the toolbar.
	 * See mw.toolbar.addButtons for parameter documentation.
	 */
	function insertButtons( buttons /* imageFile */, speedTip, tagOpen, tagClose, sampleText, imageId, selectText ) {
		var i, b, bList, $image,
			insert = function (tagOpen, tagClose, sampleText, selectText) {
				return function () {
					toolbar.insertTags( tagOpen, tagClose, sampleText, selectText );
					return false;
				};
			};
		if ( typeof buttons === 'object' ) {
			bList = $.isArray( buttons ) ? buttons : [ buttons ];
		} else {
			bList = [ slice.call( arguments ) ];
		}
		for ( i = 0; i < bList.length; i++ ) {
			b = bList[i];
			if ( $.isArray( b ) ) {
				// Backwards compatibility
				b = {
					imageFile: b[0],
					speedTip: b[1],
					tagOpen: b[2],
					tagClose: b[3],
					sampleText: b[4],
					imageId: b[5],
					selectText: b[6]
				};
			}

			$image = $( '<img>', {
				width : 23,
				height: 22,
				src   : b.imageFile,
				alt   : b.speedTip,
				title : b.speedTip,
				id    : b.imageId || undefined,
				'class': 'mw-toolbar-editbutton'
			} ).click( insert( b.tagOpen, b.tagClose, b.sampleText, b.selectText ) );

			$toolbar.append( $image );
		}
		return true;
	}

	toolbar = {
		/**
		 * Add buttons to the toolbar.
		 * Takes care of race conditions and time-based dependencies
		 * by placing buttons in a queue if this method is called before
		 * the toolbar is created.
		 * @param {Array|Object} button: Object or array of objects with the following properties:
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
		addButtons: function ( buttons ) {
			if ( isReady ) {
				insertButtons.apply( toolbar, arguments );
			} else {
				if ( typeof buttons === 'object' ) {
					if ( $.isArray( buttons ) ) {
						queue = queue.concat( buttons );
					} else {
						queue.push( buttons );
					}
				} else {
					// Convert arguments list to array
					queue.push( slice.call( arguments ) );
				}
			}
		},

		/**
		 * Add a button to the toolbar.
		 */
		addButton: function () {
			toolbar.addButtons.apply( toolbar, arguments );
		},

		/**
		 * Apply tagOpen/tagClose to selection in textarea,
		 * use sampleText instead of selection if there is none.
		 */
		insertTags: function ( tagOpen, tagClose, sampleText, selectText ) {
			if ( $currentFocused && $currentFocused.length ) {
				$currentFocused.textSelection(
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

	$( document ).ready( function () {
		var $iframe;

		// currentFocus is used to determine where to insert tags
		$currentFocused = $( '#wpTextbox1' );

		// Populate the selector cache for $toolbar
		$toolbar = $( '#toolbar' );

		// Legacy: Merge buttons from mwCustomEditButtons
		insertButtons( [].concat( queue, window.mwCustomEditButtons ) );

		// Clear queue
		queue.length = 0;

		// This causes further calls to addButton to go to insertion directly
		// instead of to the queue.
		// It is important that this is after the one and only loop through
		// the the queue
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
			$currentFocused = $(this);
		});

		// HACK: make $currentFocused work with the usability iframe
		// With proper focus detection support (HTML 5!) this'll be much cleaner
		// TODO: Get rid of this WikiEditor code from MediaWiki core!
		$iframe = $( '.wikiEditor-ui-text iframe' );
		if ( $iframe.length > 0 ) {
			$( $iframe.get( 0 ).contentWindow.document )
				// for IE
				.add( $iframe.get( 0 ).contentWindow.document.body )
				.focus( function () {
					$currentFocused = $iframe;
				} );
		}
	});

}( mediaWiki, jQuery ) );
