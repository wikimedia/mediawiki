( function ( mw, $ ) {
	QUnit.module( 'mediawiki.api', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
			this.server.respondImmediately = true;
			this.clock = this.sandbox.useFakeTimers();
		},
		teardown: function () {
			// https://github.com/jquery/jquery/issues/2453
			this.clock.tick();
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
		jQuery.each( bodies, function ( i, body ) {
			bodies[ i ] = [ status, headers, body ];
		} );
		return sequence( bodies );
	}

	QUnit.test( 'Basic functionality', function ( assert ) {
		QUnit.expect( 2 );
		var api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' }, '[]' ] );

		api.get( {} )
			.done( function ( data ) {
				assert.deepEqual( data, [], 'If request succeeds without errors, resolve deferred' );
			} );

		api.post( {} )
			.done( function ( data ) {
				assert.deepEqual( data, [], 'Simple POST request' );
			} );
	} );

	QUnit.test( 'API error', function ( assert ) {
		QUnit.expect( 1 );
		var api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' },
			'{ "error": { "code": "unknown_action" } }'
		] );

		api.get( { action: 'doesntexist' } )
			.fail( function ( errorCode ) {
				assert.equal( errorCode, 'unknown_action', 'API error should reject the deferred' );
			} );
	} );

	QUnit.test( 'FormData support', function ( assert ) {
		QUnit.expect( 2 );
		var api = new mw.Api();

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

		api.post( { action: 'test' }, { contentType: 'multipart/form-data' } );
	} );

	QUnit.test( 'Converting arrays to pipe-separated', function ( assert ) {
		QUnit.expect( 1 );
		var api = new mw.Api();

		this.server.respond( function ( request ) {
			assert.ok( request.url.match( /test=foo%7Cbar%7Cbaz/ ), 'Pipe-separated value was submitted' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		api.get( { test: [ 'foo', 'bar', 'baz' ] } );
	} );

	QUnit.test( 'Omitting false booleans', function ( assert ) {
		QUnit.expect( 2 );
		var api = new mw.Api();

		this.server.respond( function ( request ) {
			assert.ok( !request.url.match( /foo/ ), 'foo query parameter is not present' );
			assert.ok( request.url.match( /bar=true/ ), 'bar query parameter is present with value true' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		api.get( { foo: false, bar: true } );
	} );

	QUnit.test( 'getToken() - cached', function ( assert ) {
		QUnit.expect( 2 );
		var api = new mw.Api();

		// Get editToken for local wiki, this should not make
		// a request as it should be retrieved from mw.user.tokens.
		api.getToken( 'edit' )
			.done( function ( token ) {
				assert.ok( token.length, 'Got a token' );
			} )
			.fail( function ( err ) {
				assert.equal( '', err, 'API error' );
			} );

		assert.equal( this.server.requests.length, 0, 'Requests made' );
	} );

	QUnit.test( 'getToken() - uncached', function ( assert ) {
		QUnit.expect( 3 );
		var api = new mw.Api();

		this.server.respondWith( /type=testuncached/, [ 200, { 'Content-Type': 'application/json' },
			'{ "tokens": { "testuncachedtoken": "good" } }'
		] );

		// Get a token of a type that isn't prepopulated by user.tokens.
		// Could use "block" or "delete" here, but those could in theory
		// be added to user.tokens, use a fake one instead.
		api.getToken( 'testuncached' )
			.done( function ( token ) {
				assert.equal( token, 'good', 'The token' );
			} )
			.fail( function ( err ) {
				assert.equal( err, '', 'API error' );
			} );

		api.getToken( 'testuncached' )
			.done( function ( token ) {
				assert.equal( token, 'good', 'The cached token' );
			} )
			.fail( function ( err ) {
				assert.equal( err, '', 'API error' );
			} );

		assert.equal( this.server.requests.length, 1, 'Requests made' );
	} );

	QUnit.test( 'getToken() - error', function ( assert ) {
		QUnit.expect( 2 );
		var api = new mw.Api();

		this.server.respondWith( /type=testerror/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "error": { "code": "bite-me", "info": "Smite me, O Mighty Smiter" } }',
				'{ "tokens": { "testerrortoken": "good" } }'
			]
		) );

		// Don't cache error (bug 65268)
		api.getToken( 'testerror' ).fail( function ( err ) {
			assert.equal( err, 'bite-me', 'Expected error' );

			// Make this request after the first one has finished.
			// If we make it simultaneously we still want it to share
			// the cache, but as soon as it is fulfilled as error we
			// reject it so that the next one tries fresh.
			api.getToken( 'testerror' ).done( function ( token ) {
				assert.equal( token, 'good', 'The token' );
			} );
		} );
	} );

	QUnit.test( 'badToken()', function ( assert ) {
		QUnit.expect( 2 );
		var api = new mw.Api(),
			test = this;

		this.server.respondWith( /type=testbad/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "tokens": { "testbadtoken": "bad" } }',
				'{ "tokens": { "testbadtoken": "good" } }'
			]
		) );

		api.getToken( 'testbad' )
			.then( function () {
				api.badToken( 'testbad' );
				return api.getToken( 'testbad' );
			} )
			.then( function ( token ) {
				assert.equal( token, 'good', 'The token' );
				assert.equal( test.server.requests.length, 2, 'Requests made' );
			} );

	} );

	QUnit.test( 'postWithToken( tokenType, params )', function ( assert ) {
		QUnit.expect( 1 );
		var api = new mw.Api( { ajax: { url: '/postWithToken/api.php' } } );

		this.server.respondWith( 'GET', /type=testpost/, [ 200, { 'Content-Type': 'application/json' },
			'{ "tokens": { "testposttoken": "good" } }'
		] );
		this.server.respondWith( 'POST', /api/, function ( request ) {
			if ( request.requestBody.match( /token=good/ ) ) {
				request.respond( 200, { 'Content-Type': 'application/json' },
					'{ "example": { "foo": "quux" } }'
				);
			}
		} );

		api.postWithToken( 'testpost', { action: 'example', key: 'foo' } )
			.done( function ( data ) {
				assert.deepEqual( data, { example: { foo: 'quux' } } );
			} );
	} );

	QUnit.test( 'postWithToken( tokenType, params with assert )', function ( assert ) {
		QUnit.expect( 2 );
		var api = new mw.Api( { ajax: { url: '/postWithToken/api.php' } } );

		this.server.respondWith( /assert=user/, [ 200, { 'Content-Type': 'application/json' },
			'{ "error": { "code": "assertuserfailed", "info": "Assertion failed" } }'
		] );

		api.postWithToken( 'testassertpost', { action: 'example', key: 'foo', assert: 'user' } )
			.fail( function ( errorCode ) {
				assert.equal( errorCode, 'assertuserfailed', 'getToken fails assert' );
			} );

		assert.equal( this.server.requests.length, 1, 'Requests made' );
	} );

	QUnit.test( 'postWithToken( tokenType, params, ajaxOptions )', function ( assert ) {
		QUnit.expect( 3 );
		var api = new mw.Api();

		this.server.respond( [ 200, { 'Content-Type': 'application/json' }, '{ "example": "quux" }' ] );

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
		assert.equal( this.server.requests[ 0 ].requestHeaders[ 'X-Foo' ], 'Bar', 'Header sent' );
	} );

	QUnit.test( 'postWithToken() - badtoken', function ( assert ) {
		QUnit.expect( 1 );
		var api = new mw.Api();

		this.server.respondWith( /type=testbadtoken/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "tokens": { "testbadtokentoken": "bad" } }',
				'{ "tokens": { "testbadtokentoken": "good" } }'
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
		api.postWithToken( 'testbadtoken', { action: 'example', key: 'foo' } )
			.done( function ( data ) {
				assert.deepEqual( data, { example: { foo: 'quux' } } );
			} );
	} );

	QUnit.test( 'postWithToken() - badtoken-cached', function ( assert ) {
		QUnit.expect( 2 );
		var sequenceA,
			api = new mw.Api();

		this.server.respondWith( /type=testonce/, sequenceBodies( 200, { 'Content-Type': 'application/json' },
			[
				'{ "tokens": { "testoncetoken": "good-A" } }',
				'{ "tokens": { "testoncetoken": "good-B" } }'
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
		api.postWithToken( 'testonce', { action: 'example', key: 'foo' } )
			.done( function ( data ) {
				assert.deepEqual( data, { example: { value: 'A' } } );
			} );

		// - Request: action=example w/ token A -> badtoken error
		// - Request: new token -> B
		// - Request: action=example w/ token B -> success
		api.postWithToken( 'testonce', { action: 'example', key: 'bar' } )
			.done( function ( data ) {
				assert.deepEqual( data, { example: { value: 'B' } } );
			} );
	} );

	QUnit.module( 'mediawiki.api (2)', {
		setup: function () {
			var self = this,
				requests = this.requests = [];
			this.api = new mw.Api();
			this.sandbox.stub( jQuery, 'ajax', function () {
				var request = $.extend( {
					abort: self.sandbox.spy()
				}, $.Deferred() );
				requests.push( request );
				return request;
			} );
		}
	} );

	QUnit.test( '#abort', 3, function ( assert ) {
		this.api.get( {
			a: 1
		} );
		this.api.post( {
			b: 2
		} );
		this.api.abort();
		assert.ok( this.requests.length === 2, 'Check both requests triggered' );
		$.each( this.requests, function ( i, request ) {
			assert.ok( request.abort.calledOnce, 'abort request number ' + i );
		} );
	} );
}( mediaWiki, jQuery ) );
