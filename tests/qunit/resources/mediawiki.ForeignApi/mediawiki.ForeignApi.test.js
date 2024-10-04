QUnit.module( 'mediawiki.ForeignApi', ( hooks ) => {
	const CoreForeignApi = require( 'mediawiki.ForeignApi.core' ).ForeignApi;

	hooks.beforeEach( function () {
		this.server = this.sandbox.useFakeServer();
		this.server.respondImmediately = true;
	} );

	QUnit.test( 'origin is included in GET requests', function ( assert ) {
		const api = new CoreForeignApi( '//localhost:4242/w/api.php' );

		this.server.respond( ( request ) => {
			assert.true( /origin=/.test( request.url ), 'origin is included in GET requests' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.get( {} );
	} );

	QUnit.test( 'origin is included in POST requests', function ( assert ) {
		const api = new CoreForeignApi( '//localhost:4242/w/api.php' );

		this.server.respond( ( request ) => {
			assert.true( /origin=/.test( request.requestBody ), 'origin is included in POST request body' );
			assert.true( /origin=/.test( request.url ), 'origin is included in POST request URL, too' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.post( {} );
	} );

	QUnit.test( 'origin is not included in same-origin GET requests', function ( assert ) {
		const apiUrl = location.protocol + '//' + location.host + '/w/api.php',
			api = new CoreForeignApi( apiUrl );

		this.server.respond( ( request ) => {
			assert.strictEqual( request.url.match( /origin=.*?(?:&|$)/ ), null, 'origin is not included in GET requests' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.get( {} );
	} );

	QUnit.test( 'origin is not included in same-origin POST requests', function ( assert ) {
		const apiUrl = location.protocol + '//' + location.host + '/w/api.php',
			api = new CoreForeignApi( apiUrl );

		this.server.respond( ( request ) => {
			assert.strictEqual( request.requestBody.match( /origin=.*?(?:&|$)/ ), null, 'origin is not included in POST request body' );
			assert.strictEqual( request.url.match( /origin=.*?(?:&|$)/ ), null, 'origin is not included in POST request URL, either' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.post( {} );
	} );

} );
