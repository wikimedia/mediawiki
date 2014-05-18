( function ( mw ) {
	QUnit.module( 'mediawiki.api.parse', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
		}
	} ) );

	QUnit.test( 'Hello world', function ( assert ) {
		QUnit.expect( 1 );

		var api = new mw.Api();

		api.parse( '\'\'\'Hello world\'\'\'' ).done( function ( html ) {
			assert.equal( html, '<p><b>Hello world</b></p>' );
		} );

		this.server.respondWith( /action=parse.*&text='''Hello\+world'''/, function ( request ) {
			request.respond( 200, { 'Content-Type': 'application/json' },
				'{ "parse": { "text": { "*": "<p><b>Hello world</b></p>" } } }'
			);
		} );

		this.server.respond();
	} );
}( mediaWiki ) );
