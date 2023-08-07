'use strict';

mw.hook( 'postEdit' ).add( function () {
	const storage = require( './storage.js' );
	storage.openDatabase().then( function () {
		storage.deleteData( mw.config.get( 'wgPageName' ) );
		storage.closeDatabase();
	} );
} );
