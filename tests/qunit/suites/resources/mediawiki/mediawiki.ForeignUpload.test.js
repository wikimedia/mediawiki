( function ( mw ) {
	QUnit.module( 'mediawiki.ForeignUpload', QUnit.newMwEnvironment( {} ) );

	QUnit.test( 'Constructor check', function ( assert ) {
		QUnit.expect( 3 );
		var upload = new mw.ForeignUpload();

		assert.ok( upload, 'The ForeignUpload constructor is working.' );
		assert.strictEqual( upload.target, 'local', 'Default target host is correct' );
		assert.ok( upload.api instanceof mw.Api, 'API is local because default target is local.' );
	} );
}( mediaWiki ) );
