( function () {
	QUnit.module( 'mediawiki.api.options', QUnit.newMwEnvironment( {
		config: {
			wgUserName: 'Foo'
		},
		setup: function () {
			this.server = this.sandbox.useFakeServer();
			this.server.respondImmediately = true;
		}
	} ) );

	QUnit.test( 'saveOption', function ( assert ) {
		var api = new mw.Api(),
			stub = this.sandbox.stub( mw.Api.prototype, 'saveOptions' );

		api.saveOption( 'foo', 'bar' );

		assert.ok( stub.calledOnce, '#saveOptions called once' );
		assert.deepEqual( stub.getCall( 0 ).args, [ { foo: 'bar' } ], '#saveOptions called correctly' );
	} );

	QUnit.test( 'saveOptions without Unit Separator', function ( assert ) {
		var api = new mw.Api( { useUS: false } );

		// We need to respond to the request for token first, otherwise the other requests won't be sent
		// until after the server.respond call, which confuses sinon terribly. This sucks a lot.
		api.badToken( 'options' );
		api.getToken( 'options' );
		this.server.respond(
			/meta=tokens&type=csrf/,
			[ 200, { 'Content-Type': 'application/json' },
				'{ "query": { "tokens": { "csrftoken": "+\\\\" } } }' ]
		);

		// Requests are POST, match requestBody instead of url
		this.server.respond( function ( request ) {
			if ( !request.requestBody ) {
				// GET request for the token, already responded above
			} else if ( [
				// simple
				'action=options&format=json&formatversion=2&change=foo%3Dbar&token=%2B%5C',
				// two options
				'action=options&format=json&formatversion=2&change=foo%3Dbar%7Cbaz%3Dquux&token=%2B%5C',
				// not bundleable
				'action=options&format=json&formatversion=2&optionname=foo&optionvalue=bar%7Cquux&token=%2B%5C',
				'action=options&format=json&formatversion=2&optionname=bar&optionvalue=a%7Cb%7Cc&token=%2B%5C',
				'action=options&format=json&formatversion=2&change=baz%3Dquux&token=%2B%5C',
				// reset an option
				'action=options&format=json&formatversion=2&change=foo&token=%2B%5C',
				// reset an option, not bundleable
				'action=options&format=json&formatversion=2&optionname=foo%7Cbar%3Dquux&token=%2B%5C'
			].indexOf( request.requestBody ) !== -1 ) {
				assert.ok( true, 'Repond to ' + request.requestBody );
				request.respond( 200, { 'Content-Type': 'application/json' },
					'{ "options": "success" }' );
			} else {
				assert.ok( false, 'Unexpected request: ' + request.requestBody );
			}
		} );

		return QUnit.whenPromisesComplete(
			api.saveOptions( {} ).then( function () {
				assert.ok( true, 'Request completed: empty case' );
			} ),
			api.saveOptions( { foo: 'bar' } ).then( function () {
				assert.ok( true, 'Request completed: simple' );
			} ),
			api.saveOptions( { foo: 'bar', baz: 'quux' } ).then( function () {
				assert.ok( true, 'Request completed: two options' );
			} ),
			api.saveOptions( { foo: 'bar|quux', bar: 'a|b|c', baz: 'quux' } ).then( function () {
				assert.ok( true, 'Request completed: not bundleable' );
			} ),
			api.saveOptions( { foo: null } ).then( function () {
				assert.ok( true, 'Request completed: reset an option' );
			} ),
			api.saveOptions( { 'foo|bar=quux': null } ).then( function () {
				assert.ok( true, 'Request completed: reset an option, not bundleable' );
			} )
		);
	} );

	QUnit.test( 'saveOptions with Unit Separator', function ( assert ) {
		var api = new mw.Api( { useUS: true } );

		// We need to respond to the request for token first, otherwise the other requests won't be sent
		// until after the server.respond call, which confuses sinon terribly. This sucks a lot.
		api.badToken( 'options' );
		api.getToken( 'options' );
		this.server.respond(
			/meta=tokens&type=csrf/,
			[ 200, { 'Content-Type': 'application/json' },
				'{ "query": { "tokens": { "csrftoken": "+\\\\" } } }' ]
		);

		// Requests are POST, match requestBody instead of url
		this.server.respond( function ( request ) {
			if ( !request.requestBody ) {
				// GET request for the token, already responded above
			} else if ( [
				// simple
				'action=options&format=json&formatversion=2&change=foo%3Dbar&token=%2B%5C',
				// two options
				'action=options&format=json&formatversion=2&change=foo%3Dbar%7Cbaz%3Dquux&token=%2B%5C',
				// bundleable with unit separator
				'action=options&format=json&formatversion=2&change=%1Ffoo%3Dbar%7Cquux%1Fbar%3Da%7Cb%7Cc%1Fbaz%3Dquux&token=%2B%5C',
				// not bundleable with unit separator
				'action=options&format=json&formatversion=2&optionname=baz%3Dbaz&optionvalue=quux&token=%2B%5C',
				'action=options&format=json&formatversion=2&change=%1Ffoo%3Dbar%7Cquux%1Fbar%3Da%7Cb%7Cc&token=%2B%5C',
				// reset an option
				'action=options&format=json&formatversion=2&change=foo&token=%2B%5C',
				// reset an option, not bundleable
				'action=options&format=json&formatversion=2&optionname=foo%7Cbar%3Dquux&token=%2B%5C'
			].indexOf( request.requestBody ) !== -1 ) {
				assert.ok( true, 'Repond to ' + request.requestBody );
				request.respond(
					200,
					{ 'Content-Type': 'application/json' },
					'{ "options": "success" }'
				);
			} else {
				assert.ok( false, 'Unexpected request: ' + request.requestBody );
			}
		} );

		return QUnit.whenPromisesComplete(
			api.saveOptions( {} ).done( function () {
				assert.ok( true, 'Request completed: empty case' );
			} ),
			api.saveOptions( { foo: 'bar' } ).done( function () {
				assert.ok( true, 'Request completed: simple' );
			} ),
			api.saveOptions( { foo: 'bar', baz: 'quux' } ).done( function () {
				assert.ok( true, 'Request completed: two options' );
			} ),
			api.saveOptions( { foo: 'bar|quux', bar: 'a|b|c', baz: 'quux' } ).done( function () {
				assert.ok( true, 'Request completed: bundleable with unit separator' );
			} ),
			api.saveOptions( { foo: 'bar|quux', bar: 'a|b|c', 'baz=baz': 'quux' } ).done( function () {
				assert.ok( true, 'Request completed: not bundleable with unit separator' );
			} ),
			api.saveOptions( { foo: null } ).done( function () {
				assert.ok( true, 'Request completed: reset an option' );
			} ),
			api.saveOptions( { 'foo|bar=quux': null } ).done( function () {
				assert.ok( true, 'Request completed: reset an option, not bundleable' );
			} )
		);
	} );

	QUnit.test( 'saveOptions (anonymous)', function ( assert ) {
		var promise, test = this;

		mw.config.set( 'wgUserName', null );
		promise = new mw.Api().saveOptions( { foo: 'bar' } );

		assert.rejects( promise, /notloggedin/, 'Can not save options while not logged in' );

		return promise
			.catch( function () {
				return $.Deferred().resolve();
			} )
			.then( function () {
				assert.strictEqual( test.server.requests.length, 0, 'No requests made' );
			} );
	} );
}() );
