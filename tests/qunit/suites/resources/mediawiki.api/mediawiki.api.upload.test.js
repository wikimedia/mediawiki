( function () {
	QUnit.module( 'mediawiki.api.upload', QUnit.newMwEnvironment( {} ) );

	QUnit.test( 'Basic functionality', function ( assert ) {
		var api = new mw.Api();
		assert.ok( api.upload );
		assert.throws( function () {
			api.upload();
		} );
	} );

}() );
