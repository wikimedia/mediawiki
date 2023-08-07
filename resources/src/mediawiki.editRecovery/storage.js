/*!
 * Common indexedDB-access methods, only for use by the ResourceLoader modules this directory.
 */

const dbName = mw.config.get( 'wgDBname' ) + '_editRecovery';
const objectStoreName = 'unsaved-page-data';

var db = null;

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
 * @param {string} pageName
 * @param {string} section
 */
function loadData( pageName, section ) {
	return new Promise( function ( resolve, reject ) {
		if ( db === null ) {
			reject( 'DB not opened' );
		}
		const transaction = db.transaction( objectStoreName, 'readonly' );
		const findExisting = transaction
			.objectStore( objectStoreName )
			.get( [ pageName, section ] );
		findExisting.addEventListener( 'success', function () {
			resolve( findExisting.result );
		} );
	} );
}

/**
 * @param {string} pageName The current page name (with underscores).
 * @param {string} section The section number or null if the whole page is being edited.
 * @param {Object} pageData The page data to save.
 */
function saveData( pageName, section, pageData ) {
	return new Promise( function ( resolve, reject ) {
		if ( db === null ) {
			reject( 'DB not opened' );
		}

		// Add indexed fields.
		pageData.pageName = pageName;
		pageData.section = section;
		pageData.lastModified = Math.round( Date.now() / 1000 );

		const transaction = db.transaction( objectStoreName, 'readwrite' );
		const objectStore = transaction.objectStore( objectStoreName );

		// See if there's an existing row.
		const requestExisting = objectStore.get( [ pageName, section ] );
		requestExisting.addEventListener( 'success', function () {
			if ( requestExisting.result !== undefined ) {
				// Existing record found, so delete it and then add.
				const requestDelete = objectStore.delete( [ pageName, section ] );
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

function deleteData( pageName ) {
	return new Promise( function ( resolve, reject ) {
		if ( db === null ) {
			reject( 'DB not opened' );
		}

		const transaction = db.transaction( objectStoreName, 'readwrite' );
		const store = transaction.objectStore( objectStoreName );

		const request = store.openCursor();

		request.onsuccess = function ( event ) {
			const cursor = event.target.result;
			if ( cursor ) {
				const key = cursor.key;
				if ( key[ 0 ] === pageName ) {
					store.delete( key );
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

function closeDatabase() {
	db.close();
}

module.exports = {
	openDatabase: openDatabaseLocal,
	closeDatabase: closeDatabase,
	loadData: loadData,
	saveData: saveData,
	deleteData: deleteData
};
