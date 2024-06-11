QUnit.module( 'mediawiki.ForeignRest', ( hooks ) => {
	var CoreForeignApi = require( 'mediawiki.ForeignApi.core' ).ForeignApi;
	var CoreForeignRest = require( 'mediawiki.ForeignApi.core' ).ForeignRest;

	hooks.beforeEach( function () {
		this.server = this.sandbox.useFakeServer();
		this.server.respondImmediately = true;
		this.actionApi = new CoreForeignApi( 'http://test.example.com/api.php' );
	} );

	QUnit.test( 'get()', function ( assert ) {
		var api = new CoreForeignRest( 'http://test.example.com/rest.php', this.actionApi );

		this.server.respond( ( request ) => {
			assert.strictEqual( request.method, 'GET' );
			assert.strictEqual( request.url, 'http://test.example.com/rest.php/test/rest/path' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '{}' );
		} );

		return api.get( '/test/rest/path' ).then( ( data ) => {
			assert.deepEqual( data, {}, 'If request succeeds without errors, resolve deferred' );
		} );
	} );

	QUnit.test( 'post()', function ( assert ) {
		var api = new CoreForeignRest( 'http://test.example.com/rest.php', this.actionApi );

		this.server.respond( ( request ) => {
			assert.strictEqual( request.method, 'POST', 'Method should be POST' );
			assert.strictEqual( request.url, 'http://test.example.com/rest.php/test/bla/bla/bla', 'Url should be correct' );
			assert.true( /^application\/json/.test( request.requestHeaders[ 'Content-Type' ] ), 'Should set JSON content-type' );
			assert.strictEqual( request.requestHeaders.authorization, 'my_token', 'Should pass request header' );
			assert.deepEqual( JSON.parse( request.requestBody ), { param: 'value' }, 'Body should be correct' );
			request.respond( 201, { 'Content-Type': 'application/json' }, '{}' );
		} );

		return api.post( '/test/bla/bla/bla', {
			param: 'value'
		}, {
			authorization: 'my_token'
		} ).then( ( data ) => {
			assert.deepEqual( data, {}, 'If request succeeds without errors, resolve deferred' );
		} );
	} );

	QUnit.test( 'http error', function ( assert ) {
		var api = new CoreForeignRest( 'http://test.example.com/rest.php', this.actionApi );

		this.server.respond( [ 404, {}, 'FAIL' ] );

		api.get( '/test/rest/path' )
			.fail( ( errorCode ) => {
				assert.strictEqual( errorCode, 'http', 'API error should reject the deferred' );
			} )
			.always( assert.async() );
	} );
} );
