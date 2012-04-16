/*!
 * Scripts for action=edit
 */
( function ( mw, $ ) {
	conf = mw.config.get( [
		'wgCookiePrefix',
		'wgAction',
		'wgPageName'
	] );

	function saveToLocalStorage( draftEntry ) {
		var oldestPage,
			d,
			size = 0,
			draftsLimit = 10,
			newEntry = {
				'a': draftEntry.wpStarttime, // Age, updated for each edit session
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
				mw.log( 'Maximum of ' + draftsLimit + ' drafts reached. Removing a draft for ' + oldestPage );
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
			oldestPage;
		for ( pageName in draftsIdx ) {
			draftEntry = draftsIdx[pageName];
			if ( oldestPage === undefined || draftEntry.a < draftsIdx[oldestPage].a ) {
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

	/**
	 * @private
	 * @param {string} pageName
	 * @param {boolean} [updateIndex=true]
	 */
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
		var editBox, scrollTop, $editForm;

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
