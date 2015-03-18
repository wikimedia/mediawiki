( function ( mw ) {
	QUnit.module( 'mediawiki.api.options', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
		}
	} ) );

	QUnit.test( 'saveOption', function ( assert ) {
		QUnit.expect( 2 );

		var
			api = new mw.Api(),
			stub = this.sandbox.stub( mw.Api.prototype, 'saveOptions' );

		api.saveOption( 'foo', 'bar' );

		assert.ok( stub.calledOnce, '#saveOptions called once' );
		assert.deepEqual( stub.getCall( 0 ).args, [ { foo: 'bar' } ], '#saveOptions called correctly' );
	} );

	QUnit.test( 'saveOptions', function ( assert ) {
		QUnit.expect( 13 );

		var api = new mw.Api();

		// We need to respond to the request for token first, otherwise the other requests won't be sent
		// until after the server.respond call, which confuses sinon terribly. This sucks a lot.
		api.getToken( 'options' );
		this.server.respond(
			/action=tokens.*&type=options/,
			[ 200, { 'Content-Type': 'application/json' },
				'{ "tokens": { "optionstoken": "+\\\\" } }' ]
		);

		api.saveOptions( {} ).done( function () {
			assert.ok( true, 'Request completed: empty case' );
		} );
		api.saveOptions( { foo: 'bar' } ).done( function () {
			assert.ok( true, 'Request completed: simple' );
		} );
		api.saveOptions( { foo: 'bar', baz: 'quux' } ).done( function () {
			assert.ok( true, 'Request completed: two options' );
		} );
		api.saveOptions( { foo: 'bar|quux', bar: 'a|b|c', baz: 'quux' } ).done( function () {
			assert.ok( true, 'Request completed: not bundleable' );
		} );
		api.saveOptions( { foo: null } ).done( function () {
			assert.ok( true, 'Request completed: reset an option' );
		} );
		api.saveOptions( { 'foo|bar=quux': null } ).done( function () {
			assert.ok( true, 'Request completed: reset an option, not bundleable' );
		} );

		// Requests are POST, match requestBody instead of url
		this.server.respond( function ( request ) {
			switch ( request.requestBody ) {
				// simple
				case 'action=options&format=json&change=foo%3Dbar&token=%2B%5C':
				// two options
				case 'action=options&format=json&change=foo%3Dbar%7Cbaz%3Dquux&token=%2B%5C':
				// not bundleable
				case 'action=options&format=json&optionname=foo&optionvalue=bar%7Cquux&token=%2B%5C':
				case 'action=options&format=json&optionname=bar&optionvalue=a%7Cb%7Cc&token=%2B%5C':
				case 'action=options&format=json&change=baz%3Dquux&token=%2B%5C':
				// reset an option
				case 'action=options&format=json&change=foo&token=%2B%5C':
				// reset an option, not bundleable
				case 'action=options&format=json&optionname=foo%7Cbar%3Dquux&token=%2B%5C':
					assert.ok( true, 'Repond to ' + request.requestBody );
					request.respond( 200, { 'Content-Type': 'application/json' },
						'{ "options": "success" }' );
					break;
				default:
					assert.ok( false, 'Unexpected request:' + request.requestBody );
			}
		} );
	} );
}( mediaWiki ) );
