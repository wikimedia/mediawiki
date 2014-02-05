( function ( mw ) {
	QUnit.module( 'mediawiki.api', QUnit.newMwEnvironment( {
		setup: function () {
			this.clock = this.sandbox.useFakeTimers();
			this.server = this.sandbox.useFakeServer();
		},
		teardown: function () {
			this.clock.tick( 1 );
		}
	}) );

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

	QUnit.test( 'Deprecated callback methods', function ( assert ) {
		QUnit.expect( 3 );

		var api = new mw.Api();

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

}( mediaWiki ) );
