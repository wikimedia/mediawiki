/*!
 * Common indexedDB-access methods, only for use by the ResourceLoader modules this directory.
 */

const dbName = mw.config.get( 'wgDBname' ) + '_editRecovery';
const objectStoreName = 'unsaved-page-data';

var db = null;

// TODO: Document Promise objects as native promises, not jQuery ones.

/**
 * @return {jQuery.Promise} Promise which resolves on success
 */
function openDatabaseLocal() {
	return new Promise( function ( resolve, reject ) {
		const schemaNumber = 2;
		const openRequest = window.indexedDB.open( dbName, schemaNumber );
		openRequest.addEventListener( 'upgradeneeded', upgradeDatabase );
		openRequest.addEventListener( 'success', function ( event ) {
			db = event.target.result;
			resolve();
		} );
		openRequest.addEventListener( 'error', function ( event ) {
			reject( 'EditRecovery error: ' + event.target.error );
		} );
	} );
}

/**
 * @private
 * @param {Object} versionChangeEvent
 */
function upgradeDatabase( versionChangeEvent ) {
	const keyPathParts = [ 'pageName', 'section' ];
	var objectStore;

	if ( versionChangeEvent.oldVersion === 0 ) {
		// ObjectStore does not yet exist, create it.
		db = versionChangeEvent.target.result;
		objectStore = db.createObjectStore( objectStoreName, { keyPath: keyPathParts } );
	} else {
		// ObjectStore exists, but needs to be upgraded.
		objectStore = versionChangeEvent.target.transaction.objectStore( objectStoreName );
	}

	// Create indexes if they don't exist.
	if ( !objectStore.indexNames.contains( 'pageName-section' ) ) {
		objectStore.createIndex( 'pageName-section', keyPathParts, { unique: true } );
	}
	if ( !objectStore.indexNames.contains( 'expiryDate' ) ) {
		objectStore.createIndex( 'expiryDate', 'expiryDate' );
	}

	// Delete old indexes.
	if ( objectStore.indexNames.contains( 'lastModified' ) ) {
		objectStore.deleteIndex( 'lastModified' );
	}
}

/**
 * Load data relating to a specific page and section
 *
 * @param {string} pageName The current page name (with underscores)
 * @param {string|null} section The section ID, or null if the whole page is being edited
 * @return {jQuery.Promise} Promise which resolves with the page data on success, or rejects with an error message.
 */
function loadData( pageName, section ) {
	return new Promise( function ( resolve, reject ) {
		if ( !db ) {
			reject( 'DB not opened' );
		}
		const transaction = db.transaction( objectStoreName, 'readonly' );
		const key = [ pageName, section || '' ];
		const findExisting = transaction
			.objectStore( objectStoreName )
			.get( key );
		findExisting.addEventListener( 'success', function () {
			resolve( findExisting.result );
		} );
	} );
}

/**
 * Save data for a specific page and section
 *
 * @param {string} pageName The current page name (with underscores)
 * @param {string|null} section The section ID, or null if the whole page is being edited
 * @param {Object} pageData The page data to save
 * @return {jQuery.Promise} Promise which resolves on success, or rejects with an error message.
 */
function saveData( pageName, section, pageData ) {
	return new Promise( function ( resolve, reject ) {
		if ( !db ) {
			reject( 'DB not opened' );
		}

		// Add indexed fields.
		pageData.pageName = pageName;
		pageData.section = section || '';
		pageData.expiryDate = getExpiryDate( 30 );

		const transaction = db.transaction( objectStoreName, 'readwrite' );
		const objectStore = transaction.objectStore( objectStoreName );

		const request = objectStore.put( pageData );
		request.addEventListener( 'success', function ( event ) {
			resolve( event );
		} );
		request.addEventListener( 'error', function ( event ) {
			reject( 'Error saving data: ' + event.target.errorCode );
		} );
	} );
}

/**
 * Delete data relating to a specific page
 *
 * @param {string} pageName The current page name (with underscores)
 * @return {jQuery.Promise} Promise which resolves on success, or rejects with an error message.
 */
function deleteData( pageName ) {
	return new Promise( function ( resolve, reject ) {
		if ( !db ) {
			reject( 'DB not opened' );
		}

		const transaction = db.transaction( objectStoreName, 'readwrite' );
		const objectStore = transaction.objectStore( objectStoreName );

		const request = objectStore.openCursor();

		request.onsuccess = function ( event ) {
			const cursor = event.target.result;
			if ( cursor ) {
				const key = cursor.key;
				if ( key[ 0 ] === pageName ) {
					objectStore.delete( key );
				}
				cursor.continue();
			} else {
				resolve();
			}
		};

		request.onerror = function () {
			reject( 'Error opening cursor' );
		};
	} );
}

/**
 * Returns the date diff days in the future
 *
 * @param {number} diff Days in the future
 * @return {number} Timestamp of diff days in the future
 */
function getExpiryDate( diff ) {
	const today = new Date();
	const diffDaysFuture = new Date( today.getFullYear(), today.getMonth(), today.getDate() + diff );
	return diffDaysFuture.getTime() / 1000;
}

/**
 * Delete expired data
 *
 * @return {jQuery.Promise} Promise which resolves on success, or rejects with an error message.
 */
function deleteExpiredData() {
	return new Promise( function ( resolve, reject ) {
		if ( !db ) {
			reject( 'DB not opened' );
		}

		const transaction = db.transaction( objectStoreName, 'readwrite' );
		const objectStore = transaction.objectStore( objectStoreName );
		const expiryDate = objectStore.index( 'expiryDate' );
		const today = Date.now() / 1000;

		const expired = expiryDate.getAll( IDBKeyRange.upperBound( today, true ) );

		expired.onsuccess = function ( event ) {
			const cursors = event.target.result;
			if ( cursors ) {
				cursors.forEach( function ( cursor ) {
					deleteData( cursor.pageName );
				} );
			} else {
				resolve();
			}
		};

		expired.onerror = function () {
			reject( 'Error getting filtered data' );
		};
	} );
}

/**
 * Close database
 */
function closeDatabase() {
	if ( db ) {
		db.close();
	}
}

module.exports = {
	openDatabase: openDatabaseLocal,
	closeDatabase: closeDatabase,
	loadData: loadData,
	saveData: saveData,
	deleteData: deleteData,
	deleteExpiredData: deleteExpiredData
};
