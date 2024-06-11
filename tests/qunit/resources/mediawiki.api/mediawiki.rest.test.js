QUnit.module( 'mediawiki.rest', ( hooks ) => {
	let server;
	hooks.beforeEach( function () {
		server = this.sandbox.useFakeServer();
		server.respondImmediately = true;
	} );

	QUnit.test( 'get()', async ( assert ) => {
		const api = new mw.Rest();

		let headers;
		server.respondWith( 'GET',
			/rest.php\/test\/rest\/path\?queryParam=%2Fslash-will-be-encoded%3F$/,
			( request ) => {
				headers = request.requestHeaders;
				request.respond( 200, { 'Content-Type': 'application/json' }, '{}' );
			}
		);

		const data = await api.get(
			'/test/rest/path',
			{ queryParam: '/slash-will-be-encoded?' },
			{ MyHeader: 'MyHeaderValue' }
		);

		assert.propContains( headers, {
			MyHeader: 'MyHeaderValue'
		}, 'request headers' );
		assert.deepEqual( data, {}, 'succeeds without errors' );
	} );

	QUnit.test( 'get() respects ajaxOptions url', async ( assert ) => {
		const api = new mw.Rest( {
			ajax: {
				url: '/test.php'
			}
		} );

		server.respondWith( 'GET',
			/test.php\/test\/rest\/path$/,
			[ 200, { 'Content-Type': 'application/json' }, '{}' ]
		);

		const data = await api.get( '/test/rest/path' );
		assert.deepEqual( data, {}, 'succeeds without errors' );
	} );

	QUnit.test( 'post()', async ( assert ) => {
		const api = new mw.Rest();

		let headers, body;
		server.respondWith( 'POST',
			/rest.php\/test\/bla\/bla\/bla$/,
			( request ) => {
				headers = request.requestHeaders;
				body = request.requestBody;
				request.respond( 201, { 'Content-Type': 'application/json' }, '{}' );
			}
		);

		const data = await api.post( '/test/bla/bla/bla', {
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

	QUnit.test( 'put()', async ( assert ) => {
		const api = new mw.Rest();

		let headers, body;
		server.respondWith( 'PUT',
			/rest.php\/test\/bla\/bla\/bla$/,
			( request ) => {
				headers = request.requestHeaders;
				body = request.requestBody;
				request.respond( 201, { 'Content-Type': 'application/json' }, '{}' );
			}
		);

		const data = await api.put( '/test/bla/bla/bla', {
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

	QUnit.test( 'delete()', async ( assert ) => {
		const api = new mw.Rest();

		let headers, body;
		server.respond( 'DELETE',
			/rest.php\/test\/bla\/bla\/bla$/,
			( request ) => {
				headers = request.requestHeaders;
				body = request.requestBody;
				request.respond( 201, { 'Content-Type': 'application/json' }, '{}' );
			}
		);

		const data = await api.delete( '/test/bla/bla/bla', {
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

	QUnit.test( 'http error', ( assert ) => {
		const api = new mw.Rest();

		server.respond( [ 404, {}, 'FAIL' ] );

		const promise = api.get( '/test/rest/path' );
		assert.rejects( promise, /http/, 'API error should reject the deferred' );
	} );

	QUnit.test( '#abort', function ( assert ) {
		const requests = [];
		const api = new mw.Rest();
		this.sandbox.stub( $, 'ajax', () => {
			const request = $.Deferred();
			request.abort = this.sandbox.spy();
			requests.push( request );
			return request;
		} );
		api.get( '/test1' );
		api.post( '/test2', { a: 1 } );
		api.abort();
		assert.strictEqual( requests.length, 2, 'Check both requests triggered' );
		requests.forEach( ( request, i ) => {
			assert.true( request.abort.calledOnce, 'abort request number ' + i );
		} );
	} );
} );
