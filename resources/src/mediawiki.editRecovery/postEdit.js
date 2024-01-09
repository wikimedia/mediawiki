'use strict';

mw.hook( 'postEdit' ).add( function () {
	const storage = require( './storage.js' );
	storage.openDatabase().then( function () {
		const pageName = mw.config.get( 'wgPageName' );
		const section = mw.storage.session.get( pageName + '-editRecoverySection' ) || null;
		// Delete the localStorage item
		mw.storage.session.remove( pageName + '-editRecoverySection' );
		storage.deleteData( pageName, section );
		storage.closeDatabase();
	} );
} );
