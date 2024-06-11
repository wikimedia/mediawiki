'use strict';

mw.hook( 'postEdit' ).add( () => {
	// Only continue to delete the data if the data-saved flag hasn't been set in ./edit.js
	if ( !mw.storage.session.get( 'EditRecovery-data-saved' ) ) {
		return;
	}
	const storage = require( './storage.js' );
	storage.openDatabase().then( () => {
		const pageName = mw.config.get( 'wgPageName' );
		const section = mw.storage.session.get( pageName + '-editRecoverySection' ) || null;
		// Delete the sessionStorage items
		mw.storage.session.remove( pageName + '-editRecoverySection' );
		mw.storage.session.remove( 'EditRecovery-data-saved' );
		storage.deleteData( pageName, section );
		storage.closeDatabase();
	} );
} );
