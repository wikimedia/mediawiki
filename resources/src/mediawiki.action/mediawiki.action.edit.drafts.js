/**
 * Interface for the classic edit toolbar.
 *
 * @class mw.toolbar
 * @singleton
 */
( function ( mw, $ ) {
	var	dbPromise,
		indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.
msIndexedDB,
		conf = mw.config.get( [
			'wgCookiePrefix',
			'wgAction',
			'wgPageName'
		] ),
		draftsDBName = conf.wgCookiePrefix + "-mediawiki-drafts",
		draftsTable = "drafts";

	/**
	 * Open the database, do an update of the schema if required
	 */
	function openDraftsDatabase() {
		dbOpenPromise = $.indexedDB( draftsDBName, {
			"schema" : {
				"1" : function( transaction ) {
					var store = transaction.createObjectStore( draftsTable, {
						keyPath: 'wgPageName'
					} );
					store.createIndex( 'lastUpdated' );
				}
			}
		} ).done( function(db) {
			mw.log( 'Database ' + draftsDBName + ' opened or created' );
			// Check if there is a draft for the current page and show window
		} ).fail( function( db ) {
			mw.log.warn( 'Database could not be opened' );
		} );
		return dbOpenPromise;
	}

	function purgeDb() {
		var parsed, starttime, threshold = new Date();

		// We purge stuff after a draft has not been used in a month
		threshold = threshold.getTime() - ( 30 * 24 * 60 * 60 * 1000 );

		return $.indexedDB( draftsDBName  )
			.transaction( draftsTable )
				.progress( function ( trans ) {
					trans.objectStore( draftsTable ).index( 'lastUpdated' ).each( function ( item ) {
							parsed = new Date( )
							parsed.setTime( item.value.lastUpdated );
							if ( threshold > parsed ) {
								item.delete();
							}
						} )
				} )
				.fail( function(error) {
					mw.log.warn( 'something went wrong in the purging: ' + error);
				});
	}

	function saveToDb( draftEntry ) {
		return $.indexedDB( draftsDBName  )
			.transaction( draftsTable )
				.progress( function ( trans ) {
					trans.objectStore( draftsTable )
						.get( draftEntry.wgPageName )
						.done( function ( result /*, event*/ ) {
							draftEntry.lastUpdated = (new Date()).getTime();

							if ( !result ) {
								mw.log( 'successfully saved: ' + draftEntry.wgPageName );
								trans.objectStore( draftsTable ).add( draftEntry )
								// TODO Check max size
							} else {
								mw.log( 'successfully updated: ' + draftEntry.wgPageName );
								trans.objectStore( draftsTable ).put( draftEntry )
							}
						} )
				});
	}

	function getFromDb( pageName ) {
		return $.indexedDB( draftsDBName  )
					.objectStore(draftsTable)
					.get( wgPageName );
	}

	function removeFromDb( pageName ) {
		return $.indexedDB( draftsDBName  )
					.objectStore(draftsTable)
					.delete( wgPageName );
	}

	function textAreaAutoSaveInit() {
		var pageName = conf.wgPageName,
			$textArea = $( '#wpTextbox1' ),
			$editForm = $( '#editform' ),
			$wpStarttime = $editForm.find( '[name="wpStarttime"]' ),
			$wpEdittime = $editForm.find( '[name="wpEdittime"]' ),
			$wpSection = $editForm.find( '[name="wpSection"]' );

		$textArea.on( 'input', $.debounce( 300, function () {
			var draftEntry = {
				wgPageName: pageName,
				wpStarttime: $wpStarttime.val(),
				wpEdittime: $wpEdittime.val(),
				wpSection: $wpSection.val(),
				content: $textArea.val()
			};
			$.when( dbPromise, saveToDb( draftEntry ) )
				.fail( function ( error /*, errorEvent*/ ) {
					mw.log.warn( 'could not save entry: ' + draftEntry.wgPageName + ' ' + error );
				});
		} ) );

		$( '#wpSave, #mw-editform-cancel' ).click( function () {
			$.when( dbPromise, removeFromDb( pageName ) )
				.done( function () {
					mw.log('removed from DB: ' + pageName );
				} );
		} );

		$.when( dbPromise ).done( function () {
			getFromDb( pageName ).done( function ( currentDraft /*, event */ ) {
				var node, notif, $replaceLink, $discardLink;
				if ( currentDraft && $.trim( currentDraft ) !== $.trim( $textArea.val() ) ) {
					$replaceLink = $( '<a>' )
						.text( mw.msg( 'textarea-use-draft' ) )
						.click( function ( e ) {
							$textArea.val( currentDraft.content );
							$wpStarttime.val( currentDraft.wpStarttime );
							$wpEdittime.val( currentDraft.wpEdittime );
							$wpSection.val( currentDraft.wpSection );
							e.preventDefault();
							notif.close();
						} );

					$discardLink = $( '<a>' )
						.text( mw.msg( 'textarea-use-current-version' ) )
						.click( function ( e ) {
							$.when( dbPromise, removeFromDb( pageName ) );
							e.preventDefault();
							notif.close();
						} );

					node = $( '<span>' ).append(
						$( '<span>' ).text( mw.msg( 'textarea-draft-found' ) ),
						$( '<br>' ),
						$replaceLink,
						document.createTextNode( ' · ' ),
						$discardLink
					);
					notif = mw.notification.notify( node, { autoHide: false } );
				}
			} );
		} );
	}

	// TODO can be moved before document ready..
	if ( indexedDB && ( conf.wgAction === 'edit' || conf.wgAction === 'submit' ) ) {
		dbPromise = openDraftsDatabase();
		$.when( dbPromise, purgeDb() );
		$.when( dbPromise, $.ready ).done( textAreaAutoSaveInit );
	}

}( mediaWiki, jQuery ) );