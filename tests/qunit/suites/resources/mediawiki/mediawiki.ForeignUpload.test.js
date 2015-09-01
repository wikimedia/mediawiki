( function ( mw ) {
	QUnit.module( 'mediawiki.ForeignUpload', QUnit.newMwEnvironment( {} ) );

	QUnit.test( 'Constructor check', function ( assert ) {
		QUnit.expect( 3 );
		var upload = new mw.ForeignUpload();

		assert.ok( upload, 'The ForeignUpload constructor is working.' );
		assert.strictEqual( upload.targetHost, 'commons.wikimedia.org', 'Default target host is correct' );
		assert.ok( upload.api instanceof mw.ForeignApi, 'API is correctly configured to point at a foreign wiki.' );
	} );
}( mediaWiki ) );
