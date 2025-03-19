QUnit.module( 'mediawiki.api', ( hooks ) => {
	const originalFormData = window.FormData;
	const originalMwVersion = mw.config.get( 'wgVersion' );
	hooks.beforeEach( function () {
		this.server = this.sandbox.useFakeServer();
		this.server.respondImmediately = true;
	} );
	hooks.afterEach( () => {
		window.FormData = originalFormData;
		mw.config.set( 'wgVersion', originalMwVersion );
	} );

	function sequence( responses ) {
		let i = 0;
		return function ( request ) {
			const response = responses[ i ];
			if ( response ) {
				i++;
				request.respond( ...response );
			}
		};
	}

	function sequenceBodies( status, headers, bodies ) {
		bodies.forEach( ( body, i ) => {
			bodies[ i ] = [ status, headers, body ];
		} );
		return sequence( bodies );
	}

	// Utility to make inline use with an assert easier
	function match( text, pattern ) {
		return text.match( pattern )?.[ 1 ];
	}

	QUnit.test( 'get()', function ( assert ) {
		const api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' }, '[]' ] );

		return api.get( {} ).then( ( data ) => {
			assert.deepEqual( data, [], 'If request succeeds without errors, resolve deferred' );
		} );
	} );

	QUnit.test( 'post()', async function ( assert ) {
		const api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' }, '[]' ] );

		const data = await api.post( {} );
		assert.deepEqual( data, [], 'Simple POST request' );
	} );

	QUnit.test( 'API error errorformat=bc', async function ( assert ) {
		const api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' },
			'{ "error": { "code": "unknown_action" } }'
		] );

		await assert.rejects(
			api.get( { action: 'doesntexist' } ),
			/^unknown_action$/,
			'API error should reject the deferred'
		);
	} );

	QUnit.test( 'API error errorformat!=bc', async function ( assert ) {
		const api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' },
			'{ "errors": [ { "code": "unknown_action", "key": "unknown-error", "params": [] } ] }'
		] );

		await assert.rejects(
			api.get( { action: 'doesntexist' } ),
			/^unknown_action$/,
			'API error should reject the deferred'
		);
	} );

	QUnit.test.if( 'FormData support [native]', !!window.FormData, async function ( assert ) {
		const api = new mw.Api( { ajax: { url: '/FormData/api.php' } } );

		let request;
		this.server.respond( ( req ) => {
			request = req;
			req.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		await api.post( { action: 'test' }, { contentType: 'multipart/form-data' } );

		assert.strictEqual( request.url, '/FormData/api.php', 'no query string' );
		assert.true( request.requestBody instanceof FormData, 'Request uses FormData body' );
	} );

	QUnit.test( 'FormData support [fallback]', async function ( assert ) {
		// Disable native (restored in afterEach)
		window.FormData = undefined;

		const api = new mw.Api( { ajax: { url: '/FormData/api.php' } } );

		let request;
		this.server.respond( ( req ) => {
			request = req;
			req.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		await api.post( { action: 'test' }, { contentType: 'multipart/form-data' } );

		assert.strictEqual( request.url, '/FormData/api.php', 'no query string' );
		assert.strictEqual( request.requestBody, 'action=test&format=json', 'Request uses query string body' );
	} );

	QUnit.test( 'Converting arrays to pipe-separated (string)', async function ( assert ) {
		const api = new mw.Api();

		let url;
		this.server.respond( ( request ) => {
			url = request.url;
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		await api.get( { test: [ 'foo', 'bar', 'baz' ] } );
		assert.strictEqual( match( url, /test=([^&]+)/ ), 'foo%7Cbar%7Cbaz', 'Pipe-separated value was submitted' );
	} );

	QUnit.test( 'Converting arrays to pipe-separated (mw.Title)', async function ( assert ) {
		const api = new mw.Api();

		let url;
		this.server.respond( ( request ) => {
			url = request.url;
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		await api.get( { test: [ new mw.Title( 'Foo' ), new mw.Title( 'Bar' ) ] } );
		assert.strictEqual( match( url, /test=([^&]+)/ ), 'Foo%7CBar', 'Pipe-separated value was submitted' );
	} );

	QUnit.test( 'Converting arrays to pipe-separated (misc primitives)', async function ( assert ) {
		const api = new mw.Api();

		let url;
		this.server.respond( ( request ) => {
			url = request.url;
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		// undefined/null should become empty string
		await api.get( { test: [ true, false, undefined, null, 0, 1.2 ] } );
		assert.strictEqual( match( url, /test=([^&]+)/ ), 'true%7Cfalse%7C%7C%7C0%7C1.2', 'Pipe-separated value was submitted' );
	} );

	QUnit.test( 'Omitting false booleans', async function ( assert ) {
		const api = new mw.Api( { ajax: { url: '/booleans/api.php' } } );

		let url;
		this.server.respond( ( request ) => {
			url = request.url;
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		// "foo" must be absent, "bar" must be present
		await api.get( { foo: false, bar: true } );
		assert.strictEqual( url, '/booleans/api.php?action=query&format=json&bar=true', 'url' );
	} );

	QUnit.test( 'getToken() - cached', async function ( assert ) {
		mw.Api.resetTokenCacheForTest();

		const api = new mw.Api();

		// Get csrfToken for local wiki, this should not make
		// a request as it should be retrieved from mw.user.tokens.
		const token = await api.getToken( 'csrf' );
		assert.true( token.length > 0, 'token' );

		assert.strictEqual( this.server.requests.length, 0, 'Requests made' );
	} );

	QUnit.test( 'getToken() - uncached', async function ( assert ) {
		const api = new mw.Api();

		this.server.respondWith( /type=testuncached/, [ 200, { 'Content-Type': 'application/json' },
			'{ "query": { "tokens": { "testuncachedtoken": "good" } } }'
		] );

		// Get a token of a type that isn't prepopulated by mw.user.tokens.
		// Could use "block" or "delete" here, but those could in theory
		// be added to mw.user.tokens, so use a fake one instead.
		const token = await api.getToken( 'testuncached' );
		assert.strictEqual( token, 'good', 'token' );

		const token2 = await api.getToken( 'testuncached' );
		assert.strictEqual( token2, 'good', 'token2' );

		assert.strictEqual( this.server.requests.length, 1, 'Requests made' );
	} );

	QUnit.test( 'getToken() [api error]', async function ( assert ) {
		const api = new mw.Api();

		this.server.respondWith( /type=testerror/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "error": { "code": "bite-me", "info": "Smite me, O Mighty Smiter" } }',
				'{ "query": { "tokens": { "testerrortoken": "good" } } }'
			]
		) );

		// Error must not be cached, so that re-try may succeed (T67268)
		await assert.rejects( api.getToken( 'testerror' ), /^bite-me$/, 'Expected error' );

		const token = await api.getToken( 'testerror' );
		assert.strictEqual( token, 'good', 'The token' );
	} );

	QUnit.test( 'getToken() [no query error]', async function ( assert ) {
		const api = new mw.Api();
		// Same-origin warning and missing query in response.
		const serverRsp = {
			warnings: {
				tokens: {
					'*': 'Tokens may not be obtained when the same-origin policy is not applied.'
				}
			}
		};

		this.server.respondWith( /type=testnoquery/, [ 200, { 'Content-Type': 'application/json' },
			JSON.stringify( serverRsp )
		] );

		const promise = api.getToken( 'testnoquery' );
		await assert.rejects( promise, /^query-missing$/, 'Error code' );
		const rspParam = await promise.catch( ( err, rsp ) => rsp );
		assert.deepEqual( rspParam, serverRsp, 'response' );
	} );

	QUnit.test( 'getToken() [alias]', async function ( assert ) {
		// Cache API endpoint from default to avoid cachehit in mw.user.tokens
		const api = new mw.Api( { ajax: { url: '/postWithToken/api.php' } } );

		this.server.respondWith( /type=csrf/, [ 200, { 'Content-Type': 'application/json' },
			'{ "query": { "tokens": { "csrftoken": "csrfgood" } } }'
		] );

		// Try a type aliased by normalizeTokenType().
		const token = await api.getToken( 'email' );
		assert.strictEqual( token, 'csrfgood', 'Token' );

		assert.strictEqual( this.server.requests.length, 1, 'Requests made' );
	} );

	QUnit.test( 'badToken() [custom]', async function ( assert ) {
		const api = new mw.Api();

		this.server.respondWith( /type=testbad/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "query": { "tokens": { "testbadtoken": "bad" } } }',
				'{ "query": { "tokens": { "testbadtoken": "good" } } }'
			]
		) );

		await api.getToken( 'testbad' );
		api.badToken( 'testbad' );

		const token = await api.getToken( 'testbad' );
		assert.strictEqual( token, 'good', 'The token' );
		assert.strictEqual( this.server.requests.length, 2, 'Requests made' );
	} );

	QUnit.test( 'badToken() [alias]', async function ( assert ) {
		const api = new mw.Api( { ajax: { url: '/badTokenLegacy/api.php' } } );

		this.server.respondWith( /type=csrf/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "query": { "tokens": { "csrftoken": "badlegacy" } } }',
				'{ "query": { "tokens": { "csrftoken": "goodlegacy" } } }'
			]
		) );

		await api.getToken( 'options' );
		api.badToken( 'options' );

		const token = await api.getToken( 'options' );
		assert.strictEqual( token, 'goodlegacy', 'The token' );
		assert.strictEqual( this.server.requests.length, 2, 'Request made' );
	} );

	QUnit.test( 'postWithToken( tokenType, params )', async function ( assert ) {
		const api = new mw.Api( { ajax: { url: '/postWithToken/api.php' } } );

		this.server.respondWith( 'GET', /type=testpost/, [ 200, { 'Content-Type': 'application/json' },
			'{ "query": { "tokens": { "testposttoken": "good" } } }'
		] );
		this.server.respondWith( 'POST', /api/, ( request ) => {
			if ( request.requestBody.includes( 'token=good' ) ) {
				request.respond( 200, { 'Content-Type': 'application/json' },
					'{ "example": { "foo": "quux" } }'
				);
			}
		} );

		const data = await api.postWithToken( 'testpost', { action: 'example', key: 'foo' } );
		assert.deepEqual( data, { example: { foo: 'quux' } } );
	} );

	QUnit.test( 'postWithToken( tokenType, params with assert )', async function ( assert ) {
		const api = new mw.Api( { ajax: { url: '/postWithToken/api.php' } } );

		this.server.respondWith( /assert=user/, [ 200, { 'Content-Type': 'application/json' },
			'{ "error": { "code": "assertuserfailed", "info": "Assertion failed" } }'
		] );

		await assert.rejects(
			api.postWithToken( 'testassertpost', { action: 'example', key: 'foo', assert: 'user' } ),
			/assertuserfailed/,
			'errorcode'
		);
	} );

	QUnit.test( 'postWithToken( tokenType, params, ajaxOptions )', async function ( assert ) {
		const api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' }, '{ "example": "quux" }' ] );

		await api.postWithToken( 'csrf',
			{ action: 'example' },
			{
				headers: {
					'X-Foo': 'Bar'
				}
			}
		);
		assert.strictEqual( this.server.requests[ 0 ].requestHeaders[ 'X-Foo' ], 'Bar', 'Header sent' );

		let called = 0;
		const data = await api.postWithToken( 'csrf',
			{ action: 'example' },
			() => {
				called++;
			}
		);
		assert.strictEqual( called, 0, 'parameter cannot be a callback' );
		assert.propEqual( data, { example: 'quux' }, 'data' );
		assert.strictEqual( this.server.requests.length, 2, 'Request made' );
	} );

	QUnit.test( 'postWithToken() - badtoken', async function ( assert ) {
		const api = new mw.Api();

		this.server.respondWith( /type=testbadtoken/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "query": { "tokens": { "testbadtokentoken": "bad" } } }',
				'{ "query": { "tokens": { "testbadtokentoken": "good" } } }'
			]
		) );
		this.server.respondWith( 'POST', /api/, ( request ) => {
			if ( request.requestBody.includes( 'token=bad' ) ) {
				request.respond( 200, { 'Content-Type': 'application/json' },
					'{ "error": { "code": "badtoken" } }'
				);
			}
			if ( request.requestBody.includes( 'token=good' ) ) {
				request.respond( 200, { 'Content-Type': 'application/json' },
					'{ "example": { "foo": "quux" } }'
				);
			}
		} );

		// - Request: new token -> bad
		// - Request: action=example -> badtoken error
		// - Request: new token -> good
		// - Request: action=example -> success
		const data = await api.postWithToken( 'testbadtoken', { action: 'example', key: 'foo' } );
		assert.deepEqual( data, { example: { foo: 'quux' } } );
	} );

	QUnit.test( 'postWithToken() - badtoken-cached', async function ( assert ) {
		const api = new mw.Api();

		this.server.respondWith( /type=testonce/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "query": { "tokens": { "testoncetoken": "good-A" } } }',
				'{ "query": { "tokens": { "testoncetoken": "good-B" } } }'
			]
		) );
		const sequenceA = sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "example": { "value": "A" } }',
				'{ "error": { "code": "badtoken" } }'
			]
		);
		this.server.respondWith( 'POST', /api/, ( request ) => {
			if ( request.requestBody.includes( 'token=good-A' ) ) {
				sequenceA( request );
			} else if ( request.requestBody.includes( 'token=good-B' ) ) {
				request.respond( 200, { 'Content-Type': 'application/json' },
					'{ "example": { "value": "B" } }'
				);
			}
		} );

		// - Request: new token -> A
		// - Request: action=example
		const data = await api.postWithToken( 'testonce', { action: 'example', key: 'foo' } );
		assert.deepEqual( data, { example: { value: 'A' } } );

		// - Request: action=example w/ token A -> badtoken error
		// - Request: new token -> B
		// - Request: action=example w/ token B -> success
		const data2 = await api.postWithToken( 'testonce', { action: 'example', key: 'bar' } );
		assert.deepEqual( data2, { example: { value: 'B' } } );
	} );

	QUnit.test( '#abort', function ( assert ) {
		this.sandbox.stub( $, 'ajax', ( options ) => $.Deferred().promise( {
			abort: function () {
				assert.step( options.type + ' aborted' );
			}
		} ) );
		const api = new mw.Api();
		api.get( { a: 1 } );
		api.post( { b: 2 } );
		api.abort();

		assert.verifySteps( [
			'GET aborted',
			'POST aborted'
		] );
	} );

	function isAbortError( err ) {
		return err instanceof DOMException && err.name === 'AbortError';
	}

	function assertErrorCodeEqualsDetails( assert, promise ) {
		return promise.catch( ( code, details ) => {
			assert.strictEqual( details, code, 'details and code are the same AbortError object' );
		} );
	}

	function assertErrorTextStatus( assert, promise, expected ) {
		return promise.catch( ( code, details ) => {
			assert.strictEqual( details.textStatus, expected, 'details.textStatus' );
		} );
	}

	const cases = {
		'Simple promise': ( ajaxOptions ) => new mw.Api().get( {}, ajaxOptions ),
		'Chained promise': ( ajaxOptions ) => new mw.Api().postWithToken( 'csrf', {}, ajaxOptions )
	};

	QUnit.test.each( 'Aborting using abortable promise', cases, async function ( assert, getPromise ) {
		this.server.respondImmediately = false;
		const promise = getPromise();
		promise.abort();

		await assert.rejects( promise, /^http$/, 'error code' );
		await assertErrorTextStatus( assert, promise, 'abort' );
	} );

	QUnit.test.each( 'Aborting using abortable promise with mw.Api.AbortController', cases, async function ( assert, getPromise ) {
		this.server.respondImmediately = false;
		const abort = new mw.Api.AbortController();
		const promise = getPromise( { signal: abort.signal } );
		promise.abort();

		await assert.rejects( promise, /^http$/, 'error code' );
		await assertErrorTextStatus( assert, promise, 'abort' );
	} );

	QUnit.test.each( 'Aborting using abortable promise with native AbortController', cases, async function ( assert, getPromise ) {
		this.server.respondImmediately = false;
		const abort = new AbortController();
		const promise = getPromise( { signal: abort.signal } );
		promise.abort();

		await assert.rejects( promise, /^http$/, 'error code' );
		await assertErrorTextStatus( assert, promise, 'abort' );
	} );

	QUnit.test.each( 'Aborting using mw.Api.AbortController (pre-aborted signal)', cases, async ( assert, getPromise ) => {
		const abort = new mw.Api.AbortController();
		abort.abort();
		const promise = getPromise( { signal: abort.signal } );

		await assert.rejects( promise, isAbortError, 'AbortError instead of error code' );
		await assertErrorCodeEqualsDetails( assert, promise );
	} );

	QUnit.test.each( 'Aborting using mw.Api.AbortController (signal abort event)', cases, async function ( assert, getPromise ) {
		this.server.respondImmediately = false;
		const abort = new mw.Api.AbortController();
		const promise = getPromise( { signal: abort.signal } );
		abort.abort();

		await assert.rejects( promise, isAbortError, 'AbortError instead of error code' );
		await assertErrorCodeEqualsDetails( assert, promise );
	} );

	QUnit.test.each( 'Aborting using Native AbortController (pre-aborted signal)', cases, async ( assert, getPromise ) => {
		const abort = new AbortController();
		abort.abort();
		const promise = getPromise( { signal: abort.signal } );

		await assert.rejects( promise, isAbortError, 'AbortError instead of error code' );
		await assertErrorCodeEqualsDetails( assert, promise );
	} );

	QUnit.test.each( 'Aborting using Native AbortController (signal abort event)', cases, async function ( assert, getPromise ) {
		this.server.respondImmediately = false;
		const abort = new AbortController();
		const promise = getPromise( { signal: abort.signal } );
		abort.abort();

		await assert.rejects( promise, isAbortError, 'AbortError instead of error code' );
		await assertErrorCodeEqualsDetails( assert, promise );
	} );

	QUnit.test( 'User agent', async function ( assert ) {
		mw.config.set( 'wgVersion', 'VERSION' );

		new mw.Api().get( {} );
		new mw.Api( { userAgent: 'foo' } ).get( {} );

		assert.strictEqual( this.server.requests[ 0 ].requestHeaders[ 'Api-User-Agent' ], 'MediaWiki-JS/VERSION', 'Default user agent' );
		assert.strictEqual( this.server.requests[ 1 ].requestHeaders[ 'Api-User-Agent' ], 'foo', 'Custom user agent' );
	} );
} );
