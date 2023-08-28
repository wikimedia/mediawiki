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
	return new Promise( function ( resolve ) {
		const schemaNumber = 1;
		const openRequest = window.indexedDB.open( dbName, schemaNumber );
		openRequest.addEventListener( 'upgradeneeded', upgradeDatabase );
		openRequest.addEventListener( 'success', function ( event ) {
			db = event.target.result;
			resolve();
		} );
	} );
}

/**
 * @private
 * @param {Object} versionChangeEvent
 */
function upgradeDatabase( versionChangeEvent ) {
	db = versionChangeEvent.target.result;
	const keyPathParts = [ 'pageName', 'section' ];
	const objectStore = db.createObjectStore( objectStoreName, { keyPath: keyPathParts } );
	objectStore.createIndex( 'pageName-section', keyPathParts, { unique: true } );
	objectStore.createIndex( 'lastModified', 'lastModified' );
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
		pageData.section = section;
		pageData.lastModified = Math.round( Date.now() / 1000 );

		const transaction = db.transaction( objectStoreName, 'readwrite' );
		const objectStore = transaction.objectStore( objectStoreName );
		const key = [ pageName, section || '' ];

		// See if there's an existing row.
		const requestExisting = objectStore.get( key );
		requestExisting.addEventListener( 'success', function () {
			if ( requestExisting.result !== undefined ) {
				// Existing record found, so delete it and then add.
				const requestDelete = objectStore.delete( key );
				requestDelete.addEventListener( 'success', function () {
					const requestAdd = objectStore.add( pageData );
					requestAdd.addEventListener( 'success', function ( event ) {
						resolve( event );
					} );
				} );
			} else {
				// No existing record, so just add.
				const requestAdd = objectStore.add( pageData );
				requestAdd.addEventListener( 'success', function ( event ) {
					resolve( event );
				} );
			}
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
	deleteData: deleteData
};
