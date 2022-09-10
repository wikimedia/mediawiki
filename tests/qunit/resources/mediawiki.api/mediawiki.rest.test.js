( function () {
	QUnit.module( 'mediawiki.rest', QUnit.newMwEnvironment( {
		beforeEach: function () {
			this.server = this.sandbox.useFakeServer();
			this.server.respondImmediately = true;
		}
	} ) );

	QUnit.test( 'get()', function ( assert ) {
		var api = new mw.Rest();

		this.server.respond( function ( request ) {
			assert.strictEqual( request.method, 'GET' );
			assert.true(
				/rest.php\/test\/rest\/path\?queryParam=%2Fslash-will-be-encoded%3F$/.test( request.url ),
				'Should have correct request URL'
			);
			assert.strictEqual( request.requestHeaders.MyHeader, 'MyHeaderValue' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '{}' );
		} );

		return api.get(
			'/test/rest/path',
			{ queryParam: '/slash-will-be-encoded?' },
			{ MyHeader: 'MyHeaderValue' }
		).then( function ( data ) {
			assert.deepEqual( data, {}, 'If request succeeds without errors, resolve deferred' );
		} );
	} );

	QUnit.test( 'get() respects ajaxOptions url', function ( assert ) {
		var api = new mw.Rest( {
			ajax: {
				url: '/test.php'
			}
		} );

		this.server.respond( function ( request ) {
			assert.true( /test.php\/test\/rest\/path$/.test( request.url ), 'Should have correct request URL' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '{}' );
		} );

		return api.get( '/test/rest/path' ).then( function ( data ) {
			assert.deepEqual( data, {}, 'If request succeeds without errors, resolve deferred' );
		} );
	} );

	QUnit.test( 'post()', function ( assert ) {
		var api = new mw.Rest();

		this.server.respond( function ( request ) {
			assert.strictEqual( request.method, 'POST', 'Method should be POST' );
			assert.true( /rest.php\/test\/bla\/bla\/bla$/.test( request.url ), 'Url should be correct' );
			assert.true( /^application\/json/.test( request.requestHeaders[ 'Content-Type' ] ), 'Should set JSON content-type' );
			assert.strictEqual( request.requestHeaders.authorization, 'my_token', 'Should pass request header' );
			assert.deepEqual( JSON.parse( request.requestBody ), { param: 'value' }, 'Body should be correct' );
			request.respond( 201, { 'Content-Type': 'application/json' }, '{}' );
		} );

		return api.post( '/test/bla/bla/bla', {
			param: 'value'
		}, {
			authorization: 'my_token'
		} ).then( function ( data ) {
			assert.deepEqual( data, {}, 'If request succeeds without errors, resolve deferred' );
		} );
	} );

	QUnit.test( 'put()', function ( assert ) {
		var api = new mw.Rest();

		this.server.respond( function ( request ) {
			assert.strictEqual( request.method, 'PUT', 'Method should be PUT' );
			assert.true( /rest.php\/test\/bla\/bla\/bla$/.test( request.url ), 'Url should be correct' );
			assert.true( /^application\/json/.test( request.requestHeaders[ 'Content-Type' ] ), 'Should set JSON content-type' );
			assert.strictEqual( request.requestHeaders.authorization, 'my_token', 'Should pass request header' );
			assert.deepEqual( JSON.parse( request.requestBody ), { param: 'value' }, 'Body should be correct' );
			request.respond( 201, { 'Content-Type': 'application/json' }, '{}' );
		} );

		return api.put( '/test/bla/bla/bla', {
			param: 'value'
		}, {
			authorization: 'my_token'
		} ).then( function ( data ) {
			assert.deepEqual( data, {}, 'If request succeeds without errors, resolve deferred' );
		} );
	} );

	QUnit.test( 'delete()', function ( assert ) {
		var api = new mw.Rest();

		this.server.respond( function ( request ) {
			assert.strictEqual( request.method, 'DELETE', 'Method should be DELETE' );
			assert.true( /rest.php\/test\/bla\/bla\/bla$/.test( request.url ), 'Url should be correct' );
			assert.true( /^application\/json/.test( request.requestHeaders[ 'Content-Type' ] ), 'Should set JSON content-type' );
			assert.strictEqual( request.requestHeaders.authorization, 'my_token', 'Should pass request header' );
			assert.deepEqual( JSON.parse( request.requestBody ), { param: 'value' }, 'Body should be correct' );
			request.respond( 201, { 'Content-Type': 'application/json' }, '{}' );
		} );

		return api.delete( '/test/bla/bla/bla', {
			param: 'value'
		}, {
			authorization: 'my_token'
		} ).then( function ( data ) {
			assert.deepEqual( data, {}, 'If request succeeds without errors, resolve deferred' );
		} );
	} );

	QUnit.test( 'http error', function ( assert ) {
		var api = new mw.Rest();

		this.server.respond( [ 404, {}, 'FAIL' ] );

		api.get( '/test/rest/path' )
			.fail( function ( errorCode ) {
				assert.strictEqual( errorCode, 'http', 'API error should reject the deferred' );
			} )
			.always( assert.async() );
	} );

	QUnit.module( 'mediawiki.rest abort', {
		beforeEach: function () {
			var self = this,
				requests = this.requests = [];
			this.api = new mw.Rest();
			this.sandbox.stub( $, 'ajax', function () {
				var request = $.extend( {
					abort: self.sandbox.spy()
				}, $.Deferred() );
				requests.push( request );
				return request;
			} );
		}
	} );

	QUnit.test( '#abort', function ( assert ) {
		this.api.get( '/test1' );
		this.api.post( '/test2', { a: 1 } );
		this.api.abort();
		assert.strictEqual( this.requests.length, 2, 'Check both requests triggered' );
		this.requests.forEach( function ( request, i ) {
			assert.true( request.abort.calledOnce, 'abort request number ' + i );
		} );
	} );
}() );
