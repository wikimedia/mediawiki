/*!
 * Common indexedDB-access methods, only for use by the ResourceLoader modules this directory.
 */

const config = require( './config.json' );
const dbName = mw.config.get( 'wgDBname' ) + '_editRecovery';
const editRecoveryExpiry = config.EditRecoveryExpiry;
const objectStoreName = 'unsaved-page-data';

let db = null;

// TODO: Document Promise objects as native promises, not jQuery ones.

/**
 * @ignore
 * @return {jQuery.Promise} Promise which resolves on success
 */
function openDatabaseLocal() {
	return new Promise( ( resolve, reject ) => {
		const schemaNumber = 3;
		const openRequest = window.indexedDB.open( dbName, schemaNumber );
		openRequest.addEventListener( 'upgradeneeded', upgradeDatabase );
		openRequest.addEventListener( 'success', ( event ) => {
			db = event.target.result;
			resolve();
		} );
		openRequest.addEventListener( 'error', ( event ) => {
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
	let objectStore;

	db = versionChangeEvent.target.result;
	if ( !db.objectStoreNames.contains( objectStoreName ) ) {
		// ObjectStore does not yet exist, create it.
		objectStore = db.createObjectStore( objectStoreName, { keyPath: keyPathParts } );
	} else {
		// ObjectStore exists, but needs to be upgraded.
		objectStore = versionChangeEvent.target.transaction.objectStore( objectStoreName );
	}

	// Create indexes if they don't exist.
	if ( !objectStore.indexNames.contains( 'pageName-section' ) ) {
		objectStore.createIndex( 'pageName-section', keyPathParts, { unique: true } );
	}
	if ( !objectStore.indexNames.contains( 'expiry' ) ) {
		objectStore.createIndex( 'expiry', 'expiry' );
	}

	// Delete old indexes.
	if ( objectStore.indexNames.contains( 'lastModified' ) ) {
		objectStore.deleteIndex( 'lastModified' );
	}
	if ( objectStore.indexNames.contains( 'expiryDate' ) ) {
		objectStore.deleteIndex( 'expiryDate' );
	}
}

/**
 * Load data relating to a specific page and section
 *
 * @ignore
 * @param {string} pageName The current page name (with underscores)
 * @param {string|null} section The section ID, or null if the whole page is being edited
 * @return {jQuery.Promise} Promise which resolves with the page data on success, or rejects with an error message.
 */
function loadData( pageName, section ) {
	return new Promise( ( resolve, reject ) => {
		if ( !db ) {
			reject( 'DB not opened' );
		}
		const transaction = db.transaction( objectStoreName, 'readonly' );
		const key = [ pageName, section || '' ];
		const findExisting = transaction
			.objectStore( objectStoreName )
			.get( key );
		findExisting.addEventListener( 'success', () => {
			resolve( findExisting.result );
		} );
	} );
}

function loadAllData() {
	return new Promise( ( resolve, reject ) => {
		if ( !db ) {
			reject( 'DB not opened' );
		}
		const transaction = db.transaction( objectStoreName, 'readonly' );
		const requestAll = transaction
			.objectStore( objectStoreName )
			.getAll();
		requestAll.addEventListener( 'success', () => {
			resolve( requestAll.result );
		} );
	} );
}

/**
 * Save data for a specific page and section
 *
 * @ignore
 * @param {string} pageName The current page name (with underscores)
 * @param {string|null} section The section ID, or null if the whole page is being edited
 * @param {Object} pageData The page data to save
 * @return {jQuery.Promise} Promise which resolves on success, or rejects with an error message.
 */
function saveData( pageName, section, pageData ) {
	return new Promise( ( resolve, reject ) => {
		if ( !db ) {
			reject( 'DB not opened' );
		}

		// Add indexed fields.
		pageData.pageName = pageName;
		pageData.section = section || '';
		pageData.expiry = getExpiryDate( editRecoveryExpiry );

		const transaction = db.transaction( objectStoreName, 'readwrite' );
		const objectStore = transaction.objectStore( objectStoreName );

		const request = objectStore.put( pageData );
		request.addEventListener( 'success', ( event ) => {
			resolve( event );
		} );
		request.addEventListener( 'error', ( event ) => {
			reject( 'Error saving data: ' + event.target.errorCode );
		} );
	} );
}

/**
 * Delete data relating to a specific page
 *
 * @ignore
 * @param {string} pageName The current page name (with underscores)
 * @param {string|null} section The section ID, or null if the whole page is being edited
 * @return {jQuery.Promise} Promise which resolves on success, or rejects with an error message.
 */
function deleteData( pageName, section ) {
	return new Promise( ( resolve, reject ) => {
		if ( !db ) {
			reject( 'DB not opened' );
		}

		const transaction = db.transaction( objectStoreName, 'readwrite' );
		const objectStore = transaction.objectStore( objectStoreName );

		const request = objectStore.delete( [ pageName, section || '' ] );
		request.addEventListener( 'success', resolve );
		request.addEventListener( 'error', () => {
			reject( 'Error opening cursor' );
		} );
	} );
}

/**
 * Returns the date diff seconds in the future
 *
 * @ignore
 * @param {number} diff Seconds in the future
 * @return {number} Timestamp of diff days in the future
 */
function getExpiryDate( diff ) {
	return ( Date.now() / 1000 ) + diff;
}

/**
 * Delete expired data
 *
 * @ignore
 * @return {jQuery.Promise} Promise which resolves on success, or rejects with an error message.
 */
function deleteExpiredData() {
	return new Promise( ( resolve, reject ) => {
		if ( !db ) {
			reject( 'DB not opened' );
		}

		const transaction = db.transaction( objectStoreName, 'readwrite' );
		const objectStore = transaction.objectStore( objectStoreName );
		const expiry = objectStore.index( 'expiry' );
		const now = Date.now() / 1000;

		const expired = expiry.getAll( IDBKeyRange.upperBound( now, true ) );

		expired.addEventListener( 'success', ( event ) => {
			const cursors = event.target.result;
			if ( cursors.length > 0 ) {
				const deletions = [];
				cursors.forEach( ( cursor ) => {
					deletions.push( deleteData( cursor.pageName, cursor.section ) );
				} );
				Promise.all( deletions ).then( resolve );
			} else {
				resolve();
			}
		} );

		expired.addEventListener( 'error', () => {
			reject( 'Error getting filtered data' );
		} );
	} );
}

/**
 * Close database
 *
 * @ignore
 */
function closeDatabase() {
	if ( db ) {
		db.close();
	}
}

module.exports = {
	openDatabase: openDatabaseLocal,
	closeDatabase,
	loadData,
	loadAllData,
	saveData,
	deleteData,
	deleteExpiredData
};
