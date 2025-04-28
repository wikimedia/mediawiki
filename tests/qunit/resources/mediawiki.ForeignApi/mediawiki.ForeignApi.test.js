QUnit.module( 'mediawiki.ForeignApi', ( hooks ) => {
	const CoreForeignApi = require( 'mediawiki.ForeignApi.core' ).ForeignApi;

	hooks.beforeEach( function () {
		this.server = this.sandbox.useFakeServer();
		this.server.respondImmediately = true;

		// Display debug errors from mw.Api, because the default exception value is
		// a useless "http" with details like "404 Not Found" hidden in secondary arguments
		// that require a custom Deferred handler, or mw.log.
		this.sandbox.stub( mw, 'log', mw.log.warn );
	} );

	QUnit.test( 'origin is included in GET requests', async function ( assert ) {
		this.server.respondWith( 'GET', /origin=/, [ 200, { 'Content-Type': 'application/json' }, '[]' ] );

		const api = new CoreForeignApi( '//example.test/w/api.php' );
		const res = await api.get( {} );

		assert.deepEqual( res, [], 'result' );
	} );

	QUnit.test( 'origin is included in POST requests', async function ( assert ) {
		this.server.respondWith( 'POST', /origin=/, [ 200, { 'Content-Type': 'application/json' }, '[]' ] );

		const api = new CoreForeignApi( '//example.test/w/api.php' );
		const res = await api.post( {} );

		assert.deepEqual( res, [], 'result' );
		const { requestBody } = this.server.requests[ 0 ];
		assert.true( /origin=/.test( requestBody ), 'origin is included in POST request body' );
	} );

	QUnit.test( 'origin is not included in same-origin GET requests', async function ( assert ) {
		this.server.respond( [ 200, { 'Content-Type': 'application/json' }, '[]' ] );

		const api = new CoreForeignApi( location.origin + '/w/api.php' );
		const res = await api.get( {} );

		assert.deepEqual( res, [], 'result' );
		const { url } = this.server.requests[ 0 ];
		assert.strictEqual( url.match( /origin=.*?(?:&|$)/ ), null, 'origin is not included in GET requests' );
	} );

	QUnit.test( 'origin is not included in same-origin POST requests', async function ( assert ) {
		this.server.respond( [ 200, { 'Content-Type': 'application/json' }, '[]' ] );

		const api = new CoreForeignApi( location.origin + '/w/api.php' );
		const res = await api.post( {} );

		assert.deepEqual( res, [], 'result' );
		const { requestBody, url } = this.server.requests[ 0 ];
		assert.strictEqual( requestBody.match( /origin=.*?(?:&|$)/ ), null, 'origin is not included in POST request body' );
		assert.strictEqual( url.match( /origin=.*?(?:&|$)/ ), null, 'origin is not included in POST request URL, either' );
	} );
} );
