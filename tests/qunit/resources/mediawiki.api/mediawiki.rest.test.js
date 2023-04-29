( function () {
	QUnit.module( 'mediawiki.rest', QUnit.newMwEnvironment( {
		beforeEach: function () {
			this.server = this.sandbox.useFakeServer();
			this.server.respondImmediately = true;
		}
	} ) );

	QUnit.test( 'get()', async function ( assert ) {
		var api = new mw.Rest();

		var headers;
		this.server.respondWith( 'GET',
			/rest.php\/test\/rest\/path\?queryParam=%2Fslash-will-be-encoded%3F$/,
			function ( request ) {
				headers = request.requestHeaders;
				request.respond( 200, { 'Content-Type': 'application/json' }, '{}' );
			}
		);

		var data = await api.get(
			'/test/rest/path',
			{ queryParam: '/slash-will-be-encoded?' },
			{ MyHeader: 'MyHeaderValue' }
		);

		assert.propContains( headers, {
			MyHeader: 'MyHeaderValue'
		}, 'request headers' );
		assert.deepEqual( data, {}, 'succeeds without errors' );
	} );

	QUnit.test( 'get() respects ajaxOptions url', async function ( assert ) {
		var api = new mw.Rest( {
			ajax: {
				url: '/test.php'
			}
		} );

		this.server.respondWith( 'GET',
			/test.php\/test\/rest\/path$/,
			[ 200, { 'Content-Type': 'application/json' }, '{}' ]
		);

		var data = await api.get( '/test/rest/path' );
		assert.deepEqual( data, {}, 'succeeds without errors' );
	} );

	QUnit.test( 'post()', async function ( assert ) {
		var api = new mw.Rest();

		var headers, body;
		this.server.respondWith( 'POST',
			/rest.php\/test\/bla\/bla\/bla$/,
			function ( request ) {
				headers = request.requestHeaders;
				body = request.requestBody;
				request.respond( 201, { 'Content-Type': 'application/json' }, '{}' );
			}
		);

		var data = await api.post( '/test/bla/bla/bla', {
			param: 'value'
		}, {
			authorization: 'my_token'
		} );

		assert.propContains( headers, {
			'Content-Type': 'application/json;charset=utf-8',
			authorization: 'my_token'
		}, 'request headers' );
		assert.deepEqual( JSON.parse( body ), { param: 'value' }, 'body' );
		assert.deepEqual( data, {}, 'succeeds without errors' );
	} );

	QUnit.test( 'put()', async function ( assert ) {
		var api = new mw.Rest();

		var headers, body;
		this.server.respondWith( 'PUT',
			/rest.php\/test\/bla\/bla\/bla$/,
			function ( request ) {
				headers = request.requestHeaders;
				body = request.requestBody;
				request.respond( 201, { 'Content-Type': 'application/json' }, '{}' );
			}
		);

		var data = await api.put( '/test/bla/bla/bla', {
			param: 'value'
		}, {
			authorization: 'my_token'
		} );

		assert.propContains( headers, {
			'Content-Type': 'application/json;charset=utf-8',
			authorization: 'my_token'
		}, 'request headers' );
		assert.deepEqual( JSON.parse( body ), { param: 'value' }, 'body' );
		assert.deepEqual( data, {}, 'succeeds without errors' );
	} );

	QUnit.test( 'delete()', async function ( assert ) {
		var api = new mw.Rest();

		var headers, body;
		this.server.respond( 'DELETE',
			/rest.php\/test\/bla\/bla\/bla$/,
			function ( request ) {
				headers = request.requestHeaders;
				body = request.requestBody;
				request.respond( 201, { 'Content-Type': 'application/json' }, '{}' );
			}
		);

		var data = await api.delete( '/test/bla/bla/bla', {
			param: 'value'
		}, {
			authorization: 'my_token'
		} );

		assert.propContains( headers, {
			'Content-Type': 'application/json;charset=utf-8',
			authorization: 'my_token'
		}, 'request headers' );
		assert.deepEqual( JSON.parse( body ), { param: 'value' }, 'body' );
		assert.deepEqual( data, {}, 'succeeds without errors' );
	} );

	QUnit.test( 'http error', function ( assert ) {
		var api = new mw.Rest();

		this.server.respond( [ 404, {}, 'FAIL' ] );

		var promise = api.get( '/test/rest/path' );
		assert.rejects( promise, /http/, 'API error should reject the deferred' );
	} );

	QUnit.test( '#abort', function ( assert ) {
		var requests = [];
		var api = new mw.Rest();
		this.sandbox.stub( $, 'ajax', () => {
			var request = $.Deferred();
			request.abort = this.sandbox.spy();
			requests.push( request );
			return request;
		} );
		api.get( '/test1' );
		api.post( '/test2', { a: 1 } );
		api.abort();
		assert.strictEqual( requests.length, 2, 'Check both requests triggered' );
		requests.forEach( function ( request, i ) {
			assert.true( request.abort.calledOnce, 'abort request number ' + i );
		} );
	} );
}() );
