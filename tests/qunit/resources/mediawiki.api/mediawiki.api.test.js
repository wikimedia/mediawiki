QUnit.module( 'mediawiki.api', ( hooks ) => {
	hooks.beforeEach( function () {
		this.server = this.sandbox.useFakeServer();
		this.server.respondImmediately = true;
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
		const m = text.match( pattern );
		return m && m[ 1 ] || null;
	}

	QUnit.test( 'get()', function ( assert ) {
		const api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' }, '[]' ] );

		return api.get( {} ).then( ( data ) => {
			assert.deepEqual( data, [], 'If request succeeds without errors, resolve deferred' );
		} );
	} );

	QUnit.test( 'post()', function ( assert ) {
		const api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' }, '[]' ] );

		return api.post( {} ).then( ( data ) => {
			assert.deepEqual( data, [], 'Simple POST request' );
		} );
	} );

	QUnit.test( 'API error errorformat=bc', function ( assert ) {
		const api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' },
			'{ "error": { "code": "unknown_action" } }'
		] );

		api.get( { action: 'doesntexist' } )
			.fail( ( errorCode ) => {
				assert.strictEqual( errorCode, 'unknown_action', 'API error should reject the deferred' );
			} )
			.always( assert.async() );
	} );

	QUnit.test( 'API error errorformat!=bc', function ( assert ) {
		const api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' },
			'{ "errors": [ { "code": "unknown_action", "key": "unknown-error", "params": [] } ] }'
		] );

		api.get( { action: 'doesntexist' } )
			.fail( ( errorCode ) => {
				assert.strictEqual( errorCode, 'unknown_action', 'API error should reject the deferred' );
			} )
			.always( assert.async() );
	} );

	QUnit.test( 'FormData support', function ( assert ) {
		const api = new mw.Api();

		this.server.respond( ( request ) => {
			if ( window.FormData ) {
				assert.false( /action=/.test( request.url ), 'Request has no query string' );
				assert.true( request.requestBody instanceof FormData, 'Request uses FormData body' );
			} else {
				assert.false( /action=test/.test( request.url ), 'Request has no query string' );
				assert.strictEqual( request.requestBody, 'action=test&format=json', 'Request uses query string body' );
			}
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.post( { action: 'test' }, { contentType: 'multipart/form-data' } );
	} );

	QUnit.test( 'Converting arrays to pipe-separated (string)', function ( assert ) {
		const api = new mw.Api();

		this.server.respond( ( request ) => {
			assert.strictEqual( match( request.url, /test=([^&]+)/ ), 'foo%7Cbar%7Cbaz', 'Pipe-separated value was submitted' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.get( { test: [ 'foo', 'bar', 'baz' ] } );
	} );

	QUnit.test( 'Converting arrays to pipe-separated (mw.Title)', function ( assert ) {
		const api = new mw.Api();

		this.server.respond( ( request ) => {
			assert.strictEqual( match( request.url, /test=([^&]+)/ ), 'Foo%7CBar', 'Pipe-separated value was submitted' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.get( { test: [ new mw.Title( 'Foo' ), new mw.Title( 'Bar' ) ] } );
	} );

	QUnit.test( 'Converting arrays to pipe-separated (misc primitives)', function ( assert ) {
		const api = new mw.Api();

		this.server.respond( ( request ) => {
			assert.strictEqual( match( request.url, /test=([^&]+)/ ), 'true%7Cfalse%7C%7C%7C0%7C1.2', 'Pipe-separated value was submitted' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		// undefined/null will become empty string
		return api.get( { test: [ true, false, undefined, null, 0, 1.2 ] } );
	} );

	QUnit.test( 'Omitting false booleans', function ( assert ) {
		const api = new mw.Api();

		this.server.respond( ( request ) => {
			assert.false( /foo/.test( request.url ), 'foo query parameter is not present' );
			assert.true( /bar=true/.test( request.url ), 'bar query parameter is present with value true' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.get( { foo: false, bar: true } );
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

	QUnit.test( 'getToken() - error', function ( assert ) {
		const api = new mw.Api();

		this.server.respondWith( /type=testerror/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "error": { "code": "bite-me", "info": "Smite me, O Mighty Smiter" } }',
				'{ "query": { "tokens": { "testerrortoken": "good" } } }'
			]
		) );

		// Don't cache error (T67268)
		return api.getToken( 'testerror' )
			.catch( ( err ) => {
				assert.strictEqual( err, 'bite-me', 'Expected error' );

				return api.getToken( 'testerror' );
			} )
			.then( ( token ) => {
				assert.strictEqual( token, 'good', 'The token' );
			} );
	} );

	QUnit.test( 'getToken() - no query', function ( assert ) {
		const api = new mw.Api(),
			// Same-origin warning and missing query in response.
			serverRsp = {
				warnings: {
					tokens: {
						'*': 'Tokens may not be obtained when the same-origin policy is not applied.'
					}
				}
			};

		this.server.respondWith( /type=testnoquery/, [ 200, { 'Content-Type': 'application/json' },
			JSON.stringify( serverRsp )
		] );

		return api.getToken( 'testnoquery' )
			.then( () => {
				assert.fail( 'Expected response missing a query to be rejected' );
			} )
			.catch( ( err, rsp ) => {
				assert.strictEqual( err, 'query-missing', 'Expected no query error code' );
				assert.deepEqual( rsp, serverRsp );
			} );
	} );

	QUnit.test( 'getToken() - deprecated', async function ( assert ) {
		// Cache API endpoint from default to avoid cachehit in mw.user.tokens
		const api = new mw.Api( { ajax: { url: '/postWithToken/api.php' } } );

		this.server.respondWith( /type=csrf/, [ 200, { 'Content-Type': 'application/json' },
			'{ "query": { "tokens": { "csrftoken": "csrfgood" } } }'
		] );

		// Get a token of a type that is in the legacy map.
		const token = await api.getToken( 'email' );
		assert.strictEqual( token, 'csrfgood', 'Token' );

		assert.strictEqual( this.server.requests.length, 1, 'Requests made' );
	} );

	QUnit.test( 'badToken()', async function ( assert ) {
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

	QUnit.test( 'badToken( legacy )', async function ( assert ) {
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

	QUnit.test( 'postWithToken( tokenType, params )', function ( assert ) {
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

		return api.postWithToken( 'testpost', { action: 'example', key: 'foo' } )
			.then( ( data ) => {
				assert.deepEqual( data, { example: { foo: 'quux' } } );
			} );
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

	QUnit.test( 'postWithToken() - badtoken', function ( assert ) {
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
		return api.postWithToken( 'testbadtoken', { action: 'example', key: 'foo' } )
			.then( ( data ) => {
				assert.deepEqual( data, { example: { foo: 'quux' } } );
			} );
	} );

	QUnit.test( 'postWithToken() - badtoken-cached', function ( assert ) {
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
		return api.postWithToken( 'testonce', { action: 'example', key: 'foo' } )
			.then( ( data ) => {
				assert.deepEqual( data, { example: { value: 'A' } } );

				// - Request: action=example w/ token A -> badtoken error
				// - Request: new token -> B
				// - Request: action=example w/ token B -> success
				return api.postWithToken( 'testonce', { action: 'example', key: 'bar' } );
			} )
			.then( ( data ) => {
				assert.deepEqual( data, { example: { value: 'B' } } );
			} );
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
} );
