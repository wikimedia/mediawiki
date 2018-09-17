( function () {
	QUnit.module( 'mediawiki.api.parse', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
			this.server.respondImmediately = true;
		}
	} ) );

	QUnit.test( '.parse( string )', function ( assert ) {
		this.server.respondWith( /action=parse.*&text='''Hello(\+|%20)world'''/, [ 200,
			{ 'Content-Type': 'application/json' },
			'{ "parse": { "text": "<p><b>Hello world</b></p>" } }'
		] );

		return new mw.Api().parse( '\'\'\'Hello world\'\'\'' ).done( function ( html ) {
			assert.strictEqual( html, '<p><b>Hello world</b></p>', 'Parse wikitext by string' );
		} );
	} );

	QUnit.test( '.parse( Object.toString )', function ( assert ) {
		this.server.respondWith( /action=parse.*&text='''Hello(\+|%20)world'''/, [ 200,
			{ 'Content-Type': 'application/json' },
			'{ "parse": { "text": "<p><b>Hello world</b></p>" } }'
		] );

		return new mw.Api().parse( {
			toString: function () {
				return '\'\'\'Hello world\'\'\'';
			}
		} ).done( function ( html ) {
			assert.strictEqual( html, '<p><b>Hello world</b></p>', 'Parse wikitext by toString object' );
		} );
	} );

	QUnit.test( '.parse( mw.Title )', function ( assert ) {
		this.server.respondWith( /action=parse.*&page=Earth/, [ 200,
			{ 'Content-Type': 'application/json' },
			'{ "parse": { "text": "<p><b>Earth</b> is a planet.</p>" } }'
		] );

		return new mw.Api().parse( new mw.Title( 'Earth' ) ).done( function ( html ) {
			assert.strictEqual( html, '<p><b>Earth</b> is a planet.</p>', 'Parse page by Title object' );
		} );
	} );
}() );
