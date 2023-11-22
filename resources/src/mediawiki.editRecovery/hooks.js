'use strict';

mw.hook( 'postEdit' ).add( function () {
	const storage = require( './storage.js' );
	storage.openDatabase().then( function () {
		storage.deleteData( mw.config.get( 'wgPageName' ) );
		storage.closeDatabase();
	} );
} );

// Hide the link to Special:EditRecovery if there's no data to recovery.
const editRecoveryLink = document.getElementById( 'pt-editrecovery' );
if ( editRecoveryLink ) {
	mw.hook( 'wikipage.content' ).add( function () {
		const storage = require( './storage.js' );
		storage.openDatabase().then( function () {
			storage.loadAllData().then( function ( data ) {
				if ( data.length === 0 ) {
					editRecoveryLink.classList.add( 'mw-editrecovery-no-data' );
					editRecoveryLink.title = mw.msg( 'edit-recovery-link-tooltip-no-data' );
				} else {
					editRecoveryLink.classList.remove( 'mw-editrecovery-no-data' );
					editRecoveryLink.title = mw.msg( 'edit-recovery-link-tooltip-with-data', data.length );
				}
			} );
		} );
	} );
}
