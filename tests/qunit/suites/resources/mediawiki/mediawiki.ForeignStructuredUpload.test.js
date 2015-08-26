( function ( mw ) {
	QUnit.module( 'mediawiki.ForeignStructuredUpload', QUnit.newMwEnvironment( {} ) );

	QUnit.test( 'Constructor check', function ( assert ) {
		QUnit.expect( 3 );
		var upload = new mw.ForeignStructuredUpload();

		assert.ok( upload, 'The ForeignUpload constructor is working.' );
		assert.ok( upload.descriptions, 'The descriptions array was initialized properly' );
		assert.ok( upload.categories, 'The categories array was initialized properly' );
	} );

	QUnit.test( 'getText', function ( assert ) {
		QUnit.expect( 1 );

		var upload = new mw.ForeignStructuredUpload();

		// Set basic information
		upload.addDescription( 'en', 'Test description 1 2 3' );
		upload.setDate( '1776-07-04' );
		upload.addCategories( [ 'Test 1', 'Test 2' ] );
		upload.addCategories( [ 'Test 3' ] );

		// Fake the user
		this.sandbox.stub( upload, 'getUser' ).returns( 'Test user' );

		assert.strictEqual( upload.getText().trim(), '{{Information|description={{en|Test description 1 2 3}}|date=1776-07-04|source=Test user|author=Test user}}\n\n\n\n[[Category:Test 1]][[Category:Test 2]][[Category:Test 3]]' );
	} );
}( mediaWiki ) );
