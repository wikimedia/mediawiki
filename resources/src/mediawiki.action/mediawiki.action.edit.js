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
		var oldestPage,
		    d,
		    size = 0,
		    draftsLimit = 10,
		    newEntry = {
			'a': draftEntry.wpStarttime, /* Age, updated for each edit session */
			't': draftEntry.wpStarttime,
			'e': draftEntry.wpEdittime,
			's': draftEntry.wpSection
		};
		if ( !( draftEntry.pageName in draftsIdx ) ) {
			for ( d in draftsIdx ) {
				if ( draftsIdx.hasOwnProperty( d ) ) {
					size++;
				}
			}
			if ( size === draftsLimit ) {
				oldestPage = oldestDraft();
				mw.log.warn( 'Maximum of ' + draftsLimit + ' drafts reached. Removing a draft for ' + oldestPage );
				removeFromLocalStorage( oldestPage, false );
			}
			mw.log( 'Adding a draft for ' + draftEntry.pageName );
			draftsIdx[draftEntry.pageName] = newEntry;
		}
		localStorage.setItem( conf.wgCookiePrefix + '-drafts' + draftEntry.pageName, draftEntry.content );
		writeDraftsIndex();
	}

	function oldestDraft() {
		var pageName,
		    draftEntry,
		    oldestPage = null;
		for ( pageName in draftsIdx ) {
			draftEntry = draftsIdx[pageName];
			if ( oldestPage === null || draftEntry.a < draftsIdx[oldestPage].a ) {
				oldestPage = pageName;
			}
		}
		return oldestPage;
	}

	function getLocalStorage( pageName ) {
		var draftEntry = draftsIdx[pageName];
		if ( draftEntry ) {
			return {
				wpStarttime: draftEntry.t,
				wpEdittime: draftEntry.e,
				wpSection: draftEntry.s,
				content: localStorage.getItem( conf.wgCookiePrefix + '-drafts' + pageName )
			};
		}
		return null;
	}

	function removeFromLocalStorage( pageName, updateIndex ) {
		delete draftsIdx[pageName];
		localStorage.removeItem( conf.wgCookiePrefix + '-drafts' + pageName );
		if ( updateIndex === undefined || updateIndex === true ) {
			writeDraftsIndex();
		}
	}

	function readDraftsIndex() {
		try {
			draftsIdx = JSON.parse( localStorage.getItem( conf.wgCookiePrefix + '-draftsIdx' ) );
		} catch ( e ) {
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
		var draftEntry, pageName, i,
			parsed,
			starttime,
			toDelete = [],
			threshold = new Date();

		// We purge stuff after a draft has not been used in a month
		threshold.setTime( threshold.getTime() - ( 30 * 24 * 60 * 60 * 1000 ) );
		for ( pageName in draftsIdx ) {
			draftEntry = draftsIdx[pageName];
			parsed = draftEntry.a.match( /^(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})$/ );
			starttime = new Date( Date.UTC( parsed[1], parsed[2] - 1, parsed[3], parsed[4], parsed[5], parsed[6] ) );
			if ( threshold > starttime ) {
				toDelete.push( pageName );
			}
		}
		if ( toDelete.length ) {
			for ( i = 0; i < toDelete.length; i++ ) {
				pageName = toDelete[i];
				mw.log.warn( 'Removing draft older than 30 days for ' + pageName );
				removeFromLocalStorage( pageName, false );
			}
			writeDraftsIndex();
		}
	}

	function textAreaAutoSaveInit() {
		// If there's no localStorage, there's no point in doing the checks or adding listeners.
		if ( 'localStorage' in window && localStorage !== null &&
			( conf.wgAction === 'edit' || conf.wgAction === 'submit' ) ) {
			var pageName = conf.wgPageName,
				currentDraft,
				notif,
				$replaceLink,
				$discardLink,
				$textArea = $( '#wpTextbox1' ),
				$editForm = $( '#editform' ),
				$wpStarttime = $editForm.find( '[name="wpStarttime"]' ),
				$wpEdittime = $editForm.find( '[name="wpEdittime"]' ),
				$wpSection = $editForm.find( '[name="wpSection"]' ),
				node = $( '<span>' );

			readDraftsIndex();
			purgeOldDrafts();

			$textArea.on( 'input', $.debounce( 300, function () {
				setTimeout( function () {
					var draftEntry = {
						pageName: pageName,
						wpStarttime: $wpStarttime.val(),
						wpEdittime: $wpEdittime.val(),
						wpSection: $wpSection.val(),
						content: $textArea.val()
					};
					try {
						saveToLocalStorage( draftEntry );
					} catch ( e ) {
						// Assume any error is due to the quota being exceeded,
						// per http://chrisberkhout.com/blog/localstorage-errors/
						mw.log.warn( 'Unable to save draft. localStorage is full.', e );
						removeFromLocalStorage( pageName );
					}
				} );
			} ) );

			$( '#wpSave, #mw-editform-cancel' ).click( function () {
				removeFromLocalStorage( pageName );
			} );

			currentDraft = getLocalStorage( pageName );
			if ( currentDraft && $.trim( currentDraft ) !== $.trim( $textArea.val() ) ) {
				$replaceLink = $( '<a>' )
					.text( mw.msg( 'textarea-use-draft' ) )
					.click( function ( e ) {
						var draftEntry = getLocalStorage( pageName );
						draftsIdx[pageName].a = $wpStarttime.val();
						writeDraftsIndex();
						$textArea.val( draftEntry.content );
						$wpStarttime.val( draftEntry.wpStarttime );
						$wpEdittime.val( draftEntry.wpEdittime );
						$wpSection.val( draftEntry.wpSection );
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
			} );
		}

		// Apply to dynamically created textboxes as well as normal ones
		$( document ).on( 'focus', 'textarea, input:text', function () {
			$currentFocused = $( this );
		} );

		textAreaAutoSaveInit();
	} );

}( mediaWiki, jQuery ) );
