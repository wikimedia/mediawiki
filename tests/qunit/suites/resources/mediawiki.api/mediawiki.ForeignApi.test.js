( function () {
	QUnit.module( 'mediawiki.ForeignApi', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
			this.server.respondImmediately = true;
		}
	} ) );

	QUnit.test( 'origin is included in GET requests', function ( assert ) {
		var api = new mw.ForeignApi( '//localhost:4242/w/api.php' );

		this.server.respond( function ( request ) {
			assert.ok( request.url.match( /origin=/ ), 'origin is included in GET requests' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.get( {} );
	} );

	QUnit.test( 'origin is included in POST requests', function ( assert ) {
		var api = new mw.ForeignApi( '//localhost:4242/w/api.php' );

		this.server.respond( function ( request ) {
			assert.ok( request.requestBody.match( /origin=/ ), 'origin is included in POST request body' );
			assert.ok( request.url.match( /origin=/ ), 'origin is included in POST request URL, too' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.post( {} );
	} );

	QUnit.test( 'origin is not included in same-origin GET requests', function ( assert ) {
		var apiUrl = location.protocol + '//' + location.host + '/w/api.php',
			api = new mw.ForeignApi( apiUrl );

		this.server.respond( function ( request ) {
			assert.strictEqual( request.url.match( /origin=.*?(?:&|$)/ ), null, 'origin is not included in GET requests' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.get( {} );
	} );

	QUnit.test( 'origin is not included in same-origin POST requests', function ( assert ) {
		var apiUrl = location.protocol + '//' + location.host + '/w/api.php',
			api = new mw.ForeignApi( apiUrl );

		this.server.respond( function ( request ) {
			assert.strictEqual( request.requestBody.match( /origin=.*?(?:&|$)/ ), null, 'origin is not included in POST request body' );
			assert.strictEqual( request.url.match( /origin=.*?(?:&|$)/ ), null, 'origin is not included in POST request URL, either' );
			request.respond( 200, { 'Content-Type': 'application/json' }, '[]' );
		} );

		return api.post( {} );
	} );

}() );
