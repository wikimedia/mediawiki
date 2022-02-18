( function () {
	QUnit.module( 'mediawiki.api.upload', QUnit.newMwEnvironment( {} ) );

	QUnit.test( 'Basic functionality', function ( assert ) {
		var api = new mw.Api();
		assert.strictEqual( typeof api.upload, 'function' );
		assert.throws( function () {
			api.upload();
		} );
	} );

}() );
