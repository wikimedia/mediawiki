( function ( mw ) {
	QUnit.module( 'mediawiki.api', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
		}
	} ) );

	QUnit.test( 'Basic functionality', function ( assert ) {
		QUnit.expect( 2 );

		var api = new mw.Api();

		api.get( {} )
			.done( function ( data ) {
				assert.deepEqual( data, [], 'If request succeeds without errors, resolve deferred' );
			} );

		api.post( {} )
			.done( function ( data ) {
				assert.deepEqual( data, [], 'Simple POST request' );
			} );

		this.server.respond( function ( request ) {
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );
	} );

	QUnit.test( 'API error', function ( assert ) {
		QUnit.expect( 1 );

		var api = new mw.Api();

		api.get( { action: 'doesntexist' } )
			.fail( function ( errorCode ) {
				assert.equal( errorCode, 'unknown_action', 'API error should reject the deferred' );
			} );

		this.server.respond( function ( request ) {
			request.respond( 200, { 'Content-Type': 'application/json' },
				'{ "error": { "code": "unknown_action" } }'
			);
		} );
	} );

	QUnit.test( 'FormData support', function ( assert ) {
		QUnit.expect( 2 );

		var api = new mw.Api();

		api.post( { action: 'test' }, { contentType: 'multipart/form-data' } );

		this.server.respond( function ( request ) {
			if ( window.FormData ) {
				assert.ok( !request.url.match( /action=/ ), 'Request has no query string' );
				assert.ok( request.requestBody instanceof FormData, 'Request uses FormData body' );
			} else {
				assert.ok( !request.url.match( /action=test/ ), 'Request has no query string' );
				assert.equal( request.requestBody, 'action=test&format=json', 'Request uses query string body' );
			}
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );
	} );

	QUnit.test( 'Converting arrays to pipe-separated', function ( assert ) {
		QUnit.expect( 1 );

		var api = new mw.Api();
		api.get( { test: [ 'foo', 'bar', 'baz' ] } );

		this.server.respond( function ( request ) {
			assert.ok( request.url.match( /test=foo%7Cbar%7Cbaz/ ), 'Pipe-separated value was submitted' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );
	} );

	QUnit.test( 'getToken( pre-populated )', function ( assert ) {
		QUnit.expect( 2 );

		var api = new mw.Api();

		// Get editToken for local wiki, this should not make
		// a request as it should be retrieved from user.tokens.
		api.getToken( 'edit' )
			.done( function ( token ) {
				assert.ok( token.length, 'Got a token' );
			} )
			.fail( function ( err ) {
				assert.equal( '', err, 'API error' );
			} );

		assert.equal( this.server.requests.length, 0, 'Requests made' );
	} );

	QUnit.test( 'getToken()', function ( assert ) {
		QUnit.expect( 5 );

		var test = this,
			api = new mw.Api();

		// Get a token of a type that isn't prepopulated by user.tokens.
		// Could use "block" or "delete" here, but those could in theory
		// be added to user.tokens, use a fake one instead.
		api.getToken( 'testaction' )
			.done( function ( token ) {
				assert.ok( token.length, 'Got testaction token' );
			} )
			.fail( function ( err ) {
				assert.equal( err, '', 'API error' );
			} );
		api.getToken( 'testaction' )
			.done( function ( token ) {
				assert.ok( token.length, 'Got testaction token (cached)' );
			} )
			.fail( function ( err ) {
				assert.equal( err, '', 'API error' );
			} );

		// Don't cache error (bug 65268)
		api.getToken( 'testaction2' )
			.fail( function ( err ) {
				assert.equal( err, 'bite-me', 'Expected error' );
			} )
			.always( function () {
				// Make this request after the first one has finished.
				// If we make it simultaneously we still want it to share
				// the cache, but as soon as it is fulfilled as error we
				// reject it so that the next one tries fresh.
				api.getToken( 'testaction2' )
					.done( function ( token ) {
						assert.ok( token.length, 'Got testaction2 token (error was not be cached)' );
					} )
					.fail( function ( err ) {
						assert.equal( err, '', 'API error' );
					} );

				assert.equal( test.server.requests.length, 3, 'Requests made' );

				test.server.requests[2].respond( 200, { 'Content-Type': 'application/json' },
					'{ "tokens": { "testaction2token": "0123abc" } }'
				);
			} );

		this.server.requests[0].respond( 200, { 'Content-Type': 'application/json' },
			'{ "tokens": { "testactiontoken": "0123abc" } }'
		);

		this.server.requests[1].respond( 200, { 'Content-Type': 'application/json' },
			'{ "error": { "code": "bite-me", "info": "Smite me, O Mighty Smiter" } }'
		);
	} );

	QUnit.test( 'postWithToken( tokenType, params )', function ( assert ) {
		QUnit.expect( 1 );

		var api = new mw.Api( { ajax: { url: '/postWithToken/api.php' } } );

		// - Requests token
		// - Performs action=example
		api.postWithToken( 'testsimpletoken', { action: 'example', key: 'foo' } )
			.done( function ( data ) {
				assert.deepEqual( data, { example: { foo: 'quux' } } );
			} );

		this.server.requests[0].respond( 200, { 'Content-Type': 'application/json' },
			'{ "tokens": { "testsimpletokentoken": "a-bad-token" } }'
		);

		this.server.requests[1].respond( 200, { 'Content-Type': 'application/json' },
			'{ "example": { "foo": "quux" } }'
		);
	} );

	QUnit.test( 'postWithToken( tokenType, params with assert )', function ( assert ) {
		QUnit.expect( 2 );

		var api = new mw.Api( { ajax: { url: '/postWithToken/api.php' } } );

		api.postWithToken( 'testasserttoken', { action: 'example', key: 'foo', assert: 'user' } )
			.fail( function ( errorCode ) {
				assert.equal( errorCode, 'assertuserfailed', 'getToken fails assert' );
			} );

		assert.equal( this.server.requests.length, 1, 'Request for token made' );
		this.server.respondWith( /assert=user/, function ( request ) {
			request.respond(
				200,
				{ 'Content-Type': 'application/json' },
				'{ "error": { "code": "assertuserfailed", "info": "Assertion failed" } }'
			);
		} );

		this.server.respond();
	} );

	QUnit.test( 'postWithToken( tokenType, params, ajaxOptions )', function ( assert ) {
		QUnit.expect( 3 );

		var api = new mw.Api();

		api.postWithToken(
			'edit',
			{
				action: 'example'
			},
			{
				headers: {
					'X-Foo': 'Bar'
				}
			}
		);

		api.postWithToken(
			'edit',
			{
				action: 'example'
			},
			function () {
				assert.ok( false, 'This parameter cannot be a callback' );
			}
		)
		.always( function ( data ) {
			assert.equal( data.example, 'quux' );
		} );

		assert.equal( this.server.requests.length, 2, 'Request made' );
		assert.equal( this.server.requests[0].requestHeaders['X-Foo'], 'Bar', 'Header sent' );

		this.server.respond( function ( request ) {
			request.respond( 200, { 'Content-Type': 'application/json' }, '{ "example": "quux" }' );
		} );
	} );

	QUnit.test( 'postWithToken() - badtoken', function ( assert ) {
		QUnit.expect( 1 );

		var api = new mw.Api();

		// - Request: token
		// - Request: action=example -> badtoken error
		// - Request: new token
		// - Request: action=example
		api.postWithToken( 'testbadtoken', { action: 'example', key: 'foo' } )
			.done( function ( data ) {
				assert.deepEqual( data, { example: { foo: 'quux' } } );
			} );

		this.server.requests[0].respond( 200, { 'Content-Type': 'application/json' },
			'{ "tokens": { "testbadtokentoken": "a-bad-token" } }'
		);

		this.server.requests[1].respond( 200, { 'Content-Type': 'application/json' },
			'{ "error": { "code": "badtoken" } }'
		);

		this.server.requests[2].respond( 200, { 'Content-Type': 'application/json' },
			'{ "tokens": { "testbadtokentoken": "a-good-token" } }'
		);

		this.server.requests[3].respond( 200, { 'Content-Type': 'application/json' },
			'{ "example": { "foo": "quux" } }'
		);

	} );

	QUnit.test( 'postWithToken() - badtoken-cached', function ( assert ) {
		QUnit.expect( 2 );

		var api = new mw.Api();

		// - Request: token
		// - Request: action=example
		api.postWithToken( 'testbadtokencache', { action: 'example', key: 'foo' } )
			.done( function ( data ) {
				assert.deepEqual( data, { example: { foo: 'quux' } } );
			} );

		// - Cache: Try previously cached token
		// - Request: action=example -> badtoken error
		// - Request: new token
		// - Request: action=example
		api.postWithToken( 'testbadtokencache', { action: 'example', key: 'bar' } )
			.done( function ( data ) {
				assert.deepEqual( data, { example: { bar: 'quux' } } );
			} );

		this.server.requests[0].respond( 200, { 'Content-Type': 'application/json' },
			'{ "tokens": { "testbadtokencachetoken": "a-good-token-once" } }'
		);

		this.server.requests[1].respond( 200, { 'Content-Type': 'application/json' },
			'{ "example": { "foo": "quux" } }'
		);

		this.server.requests[2].respond( 200, { 'Content-Type': 'application/json' },
			'{ "error": { "code": "badtoken" } }'
		);

		this.server.requests[3].respond( 200, { 'Content-Type': 'application/json' },
			'{ "tokens": { "testbadtokencachetoken": "a-good-new-token" } }'
		);

		this.server.requests[4].respond( 200, { 'Content-Type': 'application/json' },
			'{ "example": { "bar": "quux" } }'
		);

	} );

}( mediaWiki ) );
