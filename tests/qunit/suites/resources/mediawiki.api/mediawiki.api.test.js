( function ( mw ) {
	QUnit.module( 'mediawiki.api', QUnit.newMwEnvironment( {
		setup: function () {
			this.clock = this.sandbox.useFakeTimers();
			this.server = this.sandbox.useFakeServer();
		},
		teardown: function () {
			this.clock.tick( 1 );
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
				assert.ok( !request.url.match( /action=/), 'Request has no query string' );
				assert.ok( request.requestBody instanceof FormData, 'Request uses FormData body' );
			} else {
				assert.ok( !request.url.match( /action=test/), 'Request has no query string' );
				assert.equal( request.requestBody, 'action=test&format=json', 'Request uses query string body' );
			}
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );
	} );

	QUnit.test( 'Deprecated callback methods', function ( assert ) {
		QUnit.expect( 3 );

		var api = new mw.Api();

		this.suppressWarnings();

		api.get( {}, function () {
			assert.ok( true, 'Function argument treated as success callback.' );
		} );

		api.get( {}, {
			ok: function () {
				assert.ok( true, '"ok" property treated as success callback.' );
			}
		} );

		api.get( { action: 'doesntexist' }, {
			err: function () {
				assert.ok( true, '"err" property treated as error callback.' );
			}
		} );

		this.restoreWarnings();

		this.server.respondWith( /action=query/, function ( request ) {
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		this.server.respondWith( /action=doesntexist/, function ( request ) {
			request.respond( 200, { 'Content-Type': 'application/json' },
				'{ "error": { "code": "unknown_action" } }'
			);
		} );

		this.server.respond();
	} );

	QUnit.test( 'getToken( cached )', function ( assert ) {
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

	QUnit.test( 'getToken( uncached )', function ( assert ) {
		QUnit.expect( 2 );

		var api = new mw.Api();

		// Get a token of a type that isn't prepopulated by user.tokens.
		// Could use "block" or "delete" here, but those could in theory
		// be added to user.tokens, use a fake one instead.
		api.getToken( 'testaction' )
			.done( function ( token ) {
				assert.ok( token.length, 'Got a token' );
			} )
			.fail( function ( err ) {
				assert.equal( '', err, 'API error' );
			} );

		assert.equal( this.server.requests.length, 1, 'Requests made' );

		this.server.respond( function ( request ) {
			request.respond( 200, { 'Content-Type': 'application/json' },
				'{ "tokens": { "testactiontoken": "0123abc" } }'
			);
		} );
	} );

	QUnit.test( 'postWithToken()', function ( assert ) {
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
