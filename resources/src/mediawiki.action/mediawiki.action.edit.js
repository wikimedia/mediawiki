/**
 * Interface for the classic edit toolbar.
 *
 * @class mw.toolbar
 * @singleton
 */
( function ( mw, $ ) {
	var toolbar, isReady, $toolbar, queue, slice, $currentFocused, draftsIdx,
		conf = mw.config.get( [
			'wgCookiePrefix',
			'wgAction',
			'wgArticleId'
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

	function saveToLocalStorage( draftEntry ) {
		draftsIdx[draftEntry.pageId] = {
			's' : draftEntry.wpStarttime,
			'e' : draftEntry.wpEdittime
		}
		localStorage.setItem( conf.wgCookiePrefix + '-drafts' + draftEntry.pageId, draftEntry.content );
		writeDraftsIndex();
	}

	function getLocalStorage( pageId ) {
		var draftEntry = null;
		if( draftsIdx[pageId] ) {
			draftEntry = {};
			draftEntry.wpStarttime = draftsIdx.s;
			draftEntry.wpEdittime = draftsIdx.e;
			draftEntry.content = localStorage.getItem( conf.wgCookiePrefix + '-drafts' + pageId );
		}
		return draftEntry;
	}

	function removeFromLocalStorage( pageId ) {
		delete draftsIdx.pageId;
		localStorage.removeItem( conf.wgCookiePrefix + '-drafts' + pageId );
		writeDraftsIndex();
	}

	function readDraftsIndex() {
		try {
			draftsIdx = JSON.parse( localStorage.getItem( conf.wgCookiePrefix + '-draftsIdx' ) );
		} catch (e) {
			mw.log.error( e );
		}
		if ( !draftsIdx ) {
			draftsIdx = {};
		}
	}

	function writeDraftsIndex() {
		localStorage.setItem( conf.wgCookiePrefix + '-draftsIdx', JSON.stringify( draftsIdx ) );
	}

	function purgeOldDrafts() {
		var toDelete = [],
			entry,
			threshold = new Date();

		// We purge stuff after a month
		threshold.setTime( threshold.getTime() - ( 30 * 24 * 60 * 60 * 1000 ) );
		for ( var pageId in draftsIdx ) {
			entry = draftsIdx[pageId],
				parsed = entry.s.match( /^(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})$/ ),
				starttime = Date.UTC( parsed[1], parsed[2]-1, parsed[3], parsed[4], parsed[5], parsed[6] );
			if ( threshold > starttime ) {
				toDelete.push( pageId );	
			}
		}
		if ( toDelete.length ) {
			for ( var i = 0; i < toDelete.length; i++ ) {
				delete draftsIdx[toDelete[i]];
				localStorage.removeItem( conf.wgCookiePrefix + '-drafts' + toDelete[i] );
			}
			writeDraftsIndex();
		}
	}

	function textAreaAutoSaveInit() {
		// If there's no localStorage, there's no point in doing the checks or adding listeners.
		if ( 'localStorage' in window && ( conf.wgAction === 'edit' || conf.wgAction == 'submit' ) ) {
			var pageId = conf.wgArticleId,
				currentDraft,
				$textArea = $( '#wpTextbox1' ),
				$editForm = $( '#editform' ),
				node = $( '<span>' ),
				notif,
				$replaceLink,
				$discardLink;

			readDraftsIndex();
			purgeOldDrafts();

			$textArea.on( 'input', $.debounce( 300, function () {
				setTimeout( function () {
					try {
						saveToLocalStorage( {
							pageId: pageId,
							wpStarttime: $( '[name="wpStarttime"]', $editForm ).val(),
							wpEdittime: $( '[name="wpEdittime"]', $editForm ).val(),
							content: $textArea.val()
						} );
					} catch ( e ) {
						// Assume any error is due to the quota being exceeded,
						// per http://chrisberkhout.com/blog/localstorage-errors/
						mw.log.warn( 'Caught a quota exceeded error when trying to save stuff in localstorage' );
						removeFromLocalStorage( pageId );
					}
				} );
			} ) );

			$( '#wpSave, #mw-editform-cancel' ).click( function () {
				removeFromLocalStorage( pageId );
			} );

			currentDraft = getLocalStorage( pageId );
			if ( currentDraft && $.trim( currentDraft ) !== $.trim( $textArea.val() ) ) {
				$replaceLink = $( '<a>' )
					.text( mw.msg( 'textarea-use-draft' ) )
					.click( function ( e ) {
						var draftEntry = getLocalStorage( pageId );
						$textArea.val( draftEntry.content );
						$( '[name="wpStarttime"]', $editForm ).val( draftEntry.wpStarttime );
						$( '[name="wpEdittime"]', $editForm ).val( draftEntry.wpEdittime );
						e.preventDefault();
						notif.close();
					} );

				$discardLink = $( '<a>' )
					.text( mw.msg( 'textarea-use-current-version' ) )
					.click( function ( e ) {
						removeFromLocalStorage( pageId );
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
			} );
		}

		// Apply to dynamically created textboxes as well as normal ones
		$( document ).on( 'focus', 'textarea, input:text', function () {
			$currentFocused = $( this );
		} );

		textAreaAutoSaveInit();
	} );

}( mediaWiki, jQuery ) );
