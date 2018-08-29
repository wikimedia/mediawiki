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

}() );
