QUnit.module( 'mediawiki.api.upload', () => {

	QUnit.test( 'Basic functionality', ( assert ) => {
		const api = new mw.Api();
		assert.strictEqual( typeof api.upload, 'function' );
		assert.throws( () => {
			api.upload();
		} );
	} );

} );
