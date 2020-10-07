( function () {
	QUnit.module( 'mediawiki.api', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
			this.server.respondImmediately = true;
		}
	} ) );

	function sequence( responses ) {
		var i = 0;
		return function ( request ) {
			var response = responses[ i ];
			if ( response ) {
				i++;
				request.respond.apply( request, response );
			}
		};
	}

	function sequenceBodies( status, headers, bodies ) {
		bodies.forEach( function ( body, i ) {
			bodies[ i ] = [ status, headers, body ];
		} );
		return sequence( bodies );
	}

	// Utility to make inline use with an assert easier
	function match( text, pattern ) {
		var m = text.match( pattern );
		return m && m[ 1 ] || null;
	}

	QUnit.test( 'get()', function ( assert ) {
		var api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' }, '[]' ] );

		return api.get( {} ).then( function ( data ) {
			assert.deepEqual( data, [], 'If request succeeds without errors, resolve deferred' );
		} );
	} );

	QUnit.test( 'post()', function ( assert ) {
		var api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' }, '[]' ] );

		return api.post( {} ).then( function ( data ) {
			assert.deepEqual( data, [], 'Simple POST request' );
		} );
	} );

	QUnit.test( 'API error errorformat=bc', function ( assert ) {
		var api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' },
			'{ "error": { "code": "unknown_action" } }'
		] );

		api.get( { action: 'doesntexist' } )
			.fail( function ( errorCode ) {
				assert.strictEqual( errorCode, 'unknown_action', 'API error should reject the deferred' );
			} )
			.always( assert.async() );
	} );

	QUnit.test( 'API error errorformat!=bc', function ( assert ) {
		var api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' },
			'{ "errors": [ { "code": "unknown_action", "key": "unknown-error", "params": [] } ] }'
		] );

		api.get( { action: 'doesntexist' } )
			.fail( function ( errorCode ) {
				assert.strictEqual( errorCode, 'unknown_action', 'API error should reject the deferred' );
			} )
			.always( assert.async() );
	} );

	QUnit.test( 'FormData support', function ( assert ) {
		var api = new mw.Api();

		this.server.respond( function ( request ) {
			if ( window.FormData ) {
				assert.notOk( request.url.match( /action=/ ), 'Request has no query string' );
				assert.ok( request.requestBody instanceof FormData, 'Request uses FormData body' );
			} else {
				assert.notOk( request.url.match( /action=test/ ), 'Request has no query string' );
				assert.strictEqual( request.requestBody, 'action=test&format=json', 'Request uses query string body' );
			}
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.post( { action: 'test' }, { contentType: 'multipart/form-data' } );
	} );

	QUnit.test( 'Converting arrays to pipe-separated (string)', function ( assert ) {
		var api = new mw.Api();

		this.server.respond( function ( request ) {
			assert.strictEqual( match( request.url, /test=([^&]+)/ ), 'foo%7Cbar%7Cbaz', 'Pipe-separated value was submitted' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.get( { test: [ 'foo', 'bar', 'baz' ] } );
	} );

	QUnit.test( 'Converting arrays to pipe-separated (mw.Title)', function ( assert ) {
		var api = new mw.Api();

		this.server.respond( function ( request ) {
			assert.strictEqual( match( request.url, /test=([^&]+)/ ), 'Foo%7CBar', 'Pipe-separated value was submitted' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.get( { test: [ new mw.Title( 'Foo' ), new mw.Title( 'Bar' ) ] } );
	} );

	QUnit.test( 'Converting arrays to pipe-separated (misc primitives)', function ( assert ) {
		var api = new mw.Api();

		this.server.respond( function ( request ) {
			assert.strictEqual( match( request.url, /test=([^&]+)/ ), 'true%7Cfalse%7C%7C%7C0%7C1.2', 'Pipe-separated value was submitted' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		// undefined/null will become empty string
		return api.get( { test: [ true, false, undefined, null, 0, 1.2 ] } );
	} );

	QUnit.test( 'Omitting false booleans', function ( assert ) {
		var api = new mw.Api();

		this.server.respond( function ( request ) {
			assert.notOk( request.url.match( /foo/ ), 'foo query parameter is not present' );
			assert.ok( request.url.match( /bar=true/ ), 'bar query parameter is present with value true' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.get( { foo: false, bar: true } );
	} );

	QUnit.test( 'getToken() - cached', function ( assert ) {
		var api = new mw.Api(),
			test = this;

		// Get csrfToken for local wiki, this should not make
		// a request as it should be retrieved from mw.user.tokens.
		return api.getToken( 'csrf' )
			.then( function ( token ) {
				assert.ok( token.length, 'Got a token' );
			}, function ( err ) {
				assert.strictEqual( err, '', 'API error' );
			} )
			.then( function () {
				assert.strictEqual( test.server.requests.length, 0, 'Requests made' );
			} );
	} );

	QUnit.test( 'getToken() - uncached', function ( assert ) {
		var api = new mw.Api(),
			firstDone = assert.async(),
			secondDone = assert.async();

		this.server.respondWith( /type=testuncached/, [ 200, { 'Content-Type': 'application/json' },
			'{ "query": { "tokens": { "testuncachedtoken": "good" } } }'
		] );

		// Get a token of a type that isn't prepopulated by mw.user.tokens.
		// Could use "block" or "delete" here, but those could in theory
		// be added to mw.user.tokens, so use a fake one instead.
		api.getToken( 'testuncached' )
			.done( function ( token ) {
				assert.strictEqual( token, 'good', 'The token' );
			} )
			.fail( function ( err ) {
				assert.strictEqual( err, '', 'API error' );
			} )
			.always( firstDone );

		api.getToken( 'testuncached' )
			.done( function ( token ) {
				assert.strictEqual( token, 'good', 'The cached token' );
			} )
			.fail( function ( err ) {
				assert.strictEqual( err, '', 'API error' );
			} )
			.always( secondDone );

		assert.strictEqual( this.server.requests.length, 1, 'Requests made' );
	} );

	QUnit.test( 'getToken() - error', function ( assert ) {
		var api = new mw.Api();

		this.server.respondWith( /type=testerror/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "error": { "code": "bite-me", "info": "Smite me, O Mighty Smiter" } }',
				'{ "query": { "tokens": { "testerrortoken": "good" } } }'
			]
		) );

		// Don't cache error (T67268)
		return api.getToken( 'testerror' )
			.catch( function ( err ) {
				assert.strictEqual( err, 'bite-me', 'Expected error' );

				return api.getToken( 'testerror' );
			} )
			.then( function ( token ) {
				assert.strictEqual( token, 'good', 'The token' );
			} );
	} );

	QUnit.test( 'getToken() - no query', function ( assert ) {
		var api = new mw.Api(),
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
			.then( function () { assert.fail( 'Expected response missing a query to be rejected' ); } )
			.catch( function ( err, rsp ) {
				assert.strictEqual( err, 'query-missing', 'Expected no query error code' );
				assert.deepEqual( rsp, serverRsp );
			} );
	} );

	QUnit.test( 'getToken() - deprecated', function ( assert ) {
		// Cache API endpoint from default to avoid cachehit in mw.user.tokens
		var api = new mw.Api( { ajax: { url: '/postWithToken/api.php' } } ),
			test = this;

		this.server.respondWith( /type=csrf/, [ 200, { 'Content-Type': 'application/json' },
			'{ "query": { "tokens": { "csrftoken": "csrfgood" } } }'
		] );

		// Get a token of a type that is in the legacy map.
		return api.getToken( 'email' )
			.done( function ( token ) {
				assert.strictEqual( token, 'csrfgood', 'Token' );
			} )
			.fail( function ( err ) {
				assert.strictEqual( err, '', 'API error' );
			} )
			.always( function () {
				assert.strictEqual( test.server.requests.length, 1, 'Requests made' );
			} );
	} );

	QUnit.test( 'badToken()', function ( assert ) {
		var api = new mw.Api(),
			test = this;

		this.server.respondWith( /type=testbad/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "query": { "tokens": { "testbadtoken": "bad" } } }',
				'{ "query": { "tokens": { "testbadtoken": "good" } } }'
			]
		) );

		return api.getToken( 'testbad' )
			.then( function () {
				api.badToken( 'testbad' );
				return api.getToken( 'testbad' );
			} )
			.then( function ( token ) {
				assert.strictEqual( token, 'good', 'The token' );
				assert.strictEqual( test.server.requests.length, 2, 'Requests made' );
			} );

	} );

	QUnit.test( 'badToken( legacy )', function ( assert ) {
		var api = new mw.Api( { ajax: { url: '/badTokenLegacy/api.php' } } ),
			test = this;

		this.server.respondWith( /type=csrf/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "query": { "tokens": { "csrftoken": "badlegacy" } } }',
				'{ "query": { "tokens": { "csrftoken": "goodlegacy" } } }'
			]
		) );

		return api.getToken( 'options' )
			.then( function () {
				api.badToken( 'options' );
				return api.getToken( 'options' );
			} )
			.then( function ( token ) {
				assert.strictEqual( token, 'goodlegacy', 'The token' );
				assert.strictEqual( test.server.requests.length, 2, 'Request made' );
			} );

	} );

	QUnit.test( 'postWithToken( tokenType, params )', function ( assert ) {
		var api = new mw.Api( { ajax: { url: '/postWithToken/api.php' } } );

		this.server.respondWith( 'GET', /type=testpost/, [ 200, { 'Content-Type': 'application/json' },
			'{ "query": { "tokens": { "testposttoken": "good" } } }'
		] );
		this.server.respondWith( 'POST', /api/, function ( request ) {
			if ( request.requestBody.match( /token=good/ ) ) {
				request.respond( 200, { 'Content-Type': 'application/json' },
					'{ "example": { "foo": "quux" } }'
				);
			}
		} );

		return api.postWithToken( 'testpost', { action: 'example', key: 'foo' } )
			.then( function ( data ) {
				assert.deepEqual( data, { example: { foo: 'quux' } } );
			} );
	} );

	QUnit.test( 'postWithToken( tokenType, params with assert )', function ( assert ) {
		var api = new mw.Api( { ajax: { url: '/postWithToken/api.php' } } ),
			test = this;

		this.server.respondWith( /assert=user/, [ 200, { 'Content-Type': 'application/json' },
			'{ "error": { "code": "assertuserfailed", "info": "Assertion failed" } }'
		] );

		return api.postWithToken( 'testassertpost', { action: 'example', key: 'foo', assert: 'user' } )
			// Cast error to success and vice versa
			.then( function () {
				return $.Deferred().reject( 'Unexpected success' );
			}, function ( errorCode ) {
				assert.strictEqual( errorCode, 'assertuserfailed', 'getToken fails assert' );
				return $.Deferred().resolve();
			} )
			.then( function () {
				assert.strictEqual( test.server.requests.length, 1, 'Requests made' );
			} );
	} );

	QUnit.test( 'postWithToken( tokenType, params, ajaxOptions )', function ( assert ) {
		var api = new mw.Api(),
			test = this;

		this.server.respond( [ 200, { 'Content-Type': 'application/json' }, '{ "example": "quux" }' ] );

		return api.postWithToken( 'csrf',
			{ action: 'example' },
			{
				headers: {
					'X-Foo': 'Bar'
				}
			}
		).then( function () {
			assert.strictEqual( test.server.requests[ 0 ].requestHeaders[ 'X-Foo' ], 'Bar', 'Header sent' );

			return api.postWithToken( 'csrf',
				{ action: 'example' },
				function () {
					assert.ok( false, 'This parameter cannot be a callback' );
				}
			);
		} ).then( function ( data ) {
			assert.strictEqual( data.example, 'quux' );

			assert.strictEqual( test.server.requests.length, 2, 'Request made' );
		} );
	} );

	QUnit.test( 'postWithToken() - badtoken', function ( assert ) {
		var api = new mw.Api();

		this.server.respondWith( /type=testbadtoken/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "query": { "tokens": { "testbadtokentoken": "bad" } } }',
				'{ "query": { "tokens": { "testbadtokentoken": "good" } } }'
			]
		) );
		this.server.respondWith( 'POST', /api/, function ( request ) {
			if ( request.requestBody.match( /token=bad/ ) ) {
				request.respond( 200, { 'Content-Type': 'application/json' },
					'{ "error": { "code": "badtoken" } }'
				);
			}
			if ( request.requestBody.match( /token=good/ ) ) {
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
			.then( function ( data ) {
				assert.deepEqual( data, { example: { foo: 'quux' } } );
			} );
	} );

	QUnit.test( 'postWithToken() - badtoken-cached', function ( assert ) {
		var sequenceA,
			api = new mw.Api();

		this.server.respondWith( /type=testonce/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "query": { "tokens": { "testoncetoken": "good-A" } } }',
				'{ "query": { "tokens": { "testoncetoken": "good-B" } } }'
			]
		) );
		sequenceA = sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "example": { "value": "A" } }',
				'{ "error": { "code": "badtoken" } }'
			]
		);
		this.server.respondWith( 'POST', /api/, function ( request ) {
			if ( request.requestBody.match( /token=good-A/ ) ) {
				sequenceA( request );
			} else if ( request.requestBody.match( /token=good-B/ ) ) {
				request.respond( 200, { 'Content-Type': 'application/json' },
					'{ "example": { "value": "B" } }'
				);
			}
		} );

		// - Request: new token -> A
		// - Request: action=example
		return api.postWithToken( 'testonce', { action: 'example', key: 'foo' } )
			.then( function ( data ) {
				assert.deepEqual( data, { example: { value: 'A' } } );

				// - Request: action=example w/ token A -> badtoken error
				// - Request: new token -> B
				// - Request: action=example w/ token B -> success
				return api.postWithToken( 'testonce', { action: 'example', key: 'bar' } );
			} )
			.then( function ( data ) {
				assert.deepEqual( data, { example: { value: 'B' } } );
			} );
	} );

	QUnit.module( 'mediawiki.api (2)', {
		beforeEach: function () {
			var self = this,
				requests = this.requests = [];
			this.api = new mw.Api();
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
		this.api.get( {
			a: 1
		} );
		this.api.post( {
			b: 2
		} );
		this.api.abort();
		assert.strictEqual( this.requests.length, 2, 'Check both requests triggered' );
		this.requests.forEach( function ( request, i ) {
			assert.ok( request.abort.calledOnce, 'abort request number ' + i );
		} );
	} );
}() );
