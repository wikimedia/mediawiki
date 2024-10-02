QUnit.module( 'mediawiki.api.options', QUnit.newMwEnvironment(), ( hooks ) => {
	mw.config.set( {
		wgUserName: 'Foo'
	} );

	hooks.beforeEach( function () {
		this.server = this.sandbox.useFakeServer();
		this.server.respondImmediately = true;
	} );

	QUnit.test( 'saveOption', function ( assert ) {
		const api = new mw.Api(),
			stub = this.sandbox.stub( mw.Api.prototype, 'saveOptions' );

		api.saveOption( 'foo', 'bar' );

		assert.true( stub.calledOnce, '#saveOptions called once' );
		assert.deepEqual( stub.getCall( 0 ).args, [ { foo: 'bar' }, undefined ], '#saveOptions called correctly' );
	} );

	QUnit.test( 'saveOptions without Unit Separator', async function ( assert ) {
		const api = new mw.Api( { useUS: false } );

		// We need to respond to the request for token first, otherwise the other requests won't be sent
		// until after the server.respond call, which confuses sinon terribly. This sucks a lot.
		api.badToken( 'csrf' );
		api.getToken( 'csrf' );
		this.server.respond(
			/meta=tokens&type=csrf/,
			[ 200, { 'Content-Type': 'application/json' },
				'{ "query": { "tokens": { "csrftoken": "+\\\\" } } }' ]
		);

		// Requests are POST, match requestBody instead of url
		this.server.respond( ( request ) => {
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
			].includes( request.requestBody ) ) {
				assert.true( true, 'Repond to ' + request.requestBody );
				request.respond( 200, { 'Content-Type': 'application/json' },
					'{ "options": "success" }' );
			} else {
				assert.true( false, 'Unexpected request: ' + request.requestBody );
			}
		} );

		// empty case
		await api.saveOptions( {} );
		// simple
		await api.saveOptions( { foo: 'bar' } );
		// two options
		await api.saveOptions( { foo: 'bar', baz: 'quux' } );
		// not bundleable
		await api.saveOptions( { foo: 'bar|quux', bar: 'a|b|c', baz: 'quux' } );
		// reset an option
		await api.saveOptions( { foo: null } );
		// reset an option, not bundleable
		await api.saveOptions( { 'foo|bar=quux': null } );
	} );

	QUnit.test( 'saveOptions with Unit Separator', async function ( assert ) {
		const api = new mw.Api( { useUS: true } );

		// We need to respond to the request for token first, otherwise the other requests won't be sent
		// until after the server.respond call, which confuses sinon terribly. This sucks a lot.
		api.badToken( 'csrf' );
		api.getToken( 'csrf' );
		this.server.respond(
			/meta=tokens&type=csrf/,
			[ 200, { 'Content-Type': 'application/json' },
				'{ "query": { "tokens": { "csrftoken": "+\\\\" } } }' ]
		);

		// Requests are POST, match requestBody instead of url
		this.server.respond( ( request ) => {
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
			].includes( request.requestBody ) ) {
				assert.true( true, 'Repond to ' + request.requestBody );
				request.respond(
					200,
					{ 'Content-Type': 'application/json' },
					'{ "options": "success" }'
				);
			} else {
				assert.true( false, 'Unexpected request: ' + request.requestBody );
			}
		} );

		// empty case
		await api.saveOptions( {} );
		// simple
		await api.saveOptions( { foo: 'bar' } );
		// two options
		await api.saveOptions( { foo: 'bar', baz: 'quux' } );
		// bundleable with unit separator
		await api.saveOptions( { foo: 'bar|quux', bar: 'a|b|c', baz: 'quux' } );
		// not bundleable with unit separator
		await api.saveOptions( { foo: 'bar|quux', bar: 'a|b|c', 'baz=baz': 'quux' } );
		// reset an option
		await api.saveOptions( { foo: null } );
		// reset an option, not bundleable
		await api.saveOptions( { 'foo|bar=quux': null } );
	} );

	QUnit.test( 'saveOptions (anonymous)', async function ( assert ) {
		mw.config.set( 'wgUserName', null );

		await assert.rejects(
			new mw.Api().saveOptions( { foo: 'bar' } ),
			/notloggedin/,
			'Can not save options while not logged in'
		);

		assert.strictEqual( this.server.requests.length, 0, 'No requests made' );
	} );
} );
