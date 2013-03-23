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

	$( document ).ready( function () {
		var i, buttons, b, $iframe, collapsibleLists;

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

		// Collapsible lists of categories and templates
		collapsibleLists = [
			{
				$list: $( '.templatesUsed ul' ),
				$toggler: $( '.mw-templatesUsedExplanation' ),
				cookieName: 'templates-used-list'
			},
			{
				$list: $( '.hiddencats ul' ),
				$toggler: $( '.mw-hiddenCategoriesExplanation' ),
				cookieName: 'hidden-categories-list'
			}
		];

		for ( i = 0; i < collapsibleLists.length; i++ ) {
			/*jshint loopfunc:true */
			// Use a closure for iteration-local variables
			( function ( $list, $toggler, cookieName ) {
				var $realToggler, isCollapsed;

				// Make the toggler appear clickable; the <a> is additionally styled with an arrow icon
				$toggler.find( 'p' ).wrapInner( $( '<a>' ).addClass( 'mw-editfooter-toggler' ) );
				$realToggler = $toggler.find( 'p a.mw-editfooter-toggler' );

				$list.addClass( 'mw-editfooter-list' );
				isCollapsed = $.cookie( cookieName ) !== 'expanded';

				$list.makeCollapsible( {
					$customTogglers: $toggler,
					linksPassthru: false,
					plainMode: true,
					collapsed: isCollapsed
				} );

				$realToggler.addClass( isCollapsed ? 'collapsed' : 'expanded' );

				$list.on( 'beforeExpand.mw-collapse', function () {
					$realToggler.removeClass( 'collapsed' ).addClass( 'expanded' );
					$.cookie( cookieName, 'expanded' );
				} );

				$list.on( 'beforeCollapse.mw-collapse', function () {
					$realToggler.removeClass( 'expanded' ).addClass( 'collapsed' );
					$.cookie( cookieName, 'collapsed' );
				} );
			} )( collapsibleLists[i].$list, collapsibleLists[i].$toggler, collapsibleLists[i].cookieName );
		}
	});

}( mediaWiki, jQuery ) );
