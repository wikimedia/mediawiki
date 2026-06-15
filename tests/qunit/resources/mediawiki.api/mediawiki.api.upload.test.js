QUnit.module( 'mediawiki.api.upload', () => {

	QUnit.test( 'Basic functionality', ( assert ) => {
		const api = new mw.Api();
		assert.strictEqual( typeof api.upload, 'function' );
		assert.throws( () => {
			api.upload();
		} );
	} );

	QUnit.test( 'uploadToStash forwards description params to the publish request', function ( assert ) {
		const api = new mw.Api();

		// Stub the stash leg so it resolves with a filekey without performing
		// a real FormData upload, and capture the publish request.
		this.sandbox.stub( api, 'upload' ).returns(
			$.Deferred().resolve( { upload: { result: 'Success', filekey: 'test-key' } } ).promise()
		);
		const postStub = this.sandbox.stub( api, 'postWithEditToken' ).returns(
			$.Deferred().resolve( { upload: { result: 'Success' } } ).promise()
		);

		// license/copystatus/source are not sent on the stash request; they
		// must survive to the publish request, where 'autotext' uses them
		// server-side.
		return api.uploadToStash( 'file-contents', {
			filename: 'foo.png',
			comment: 'A test file',
			license: 'self|cc-by-sa-4.0',
			copystatus: 'Own work',
			source: 'My camera'
		} ).then( ( finish ) => finish( { autotext: true } ) ).then( () => {
			assert.strictEqual( postStub.calledOnce, true, 'publish request was made' );
			const sent = postStub.getCall( 0 ).args[ 0 ];
			assert.strictEqual( sent.filekey, 'test-key', 'filekey forwarded to publish' );
			assert.strictEqual( sent.comment, 'A test file', 'comment forwarded to publish' );
			assert.strictEqual( sent.license, 'self|cc-by-sa-4.0', 'license forwarded to publish' );
			assert.strictEqual( sent.copystatus, 'Own work', 'copystatus forwarded to publish' );
			assert.strictEqual( sent.source, 'My camera', 'source forwarded to publish' );
			assert.strictEqual( sent.autotext, true, 'autotext forwarded to publish' );
		} );
	} );

} );
