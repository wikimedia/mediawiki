QUnit.module( 'mediawiki.api.edit', ( hooks ) => {
	let server;
	hooks.beforeEach( function () {
		server = this.sandbox.useFakeServer();
		server.respondImmediately = true;
	} );

	QUnit.test( '.parse( string )', async ( assert ) => {
		server.respondWith( 'POST', /api.php/, [ 200,
			{ 'Content-Type': 'application/json' },
			'{ "parse": { "text": "<p><b>Hello world</b></p>" } }'
		] );

		const html = await new mw.Api().parse( '\'\'\'Hello world\'\'\'' );
		assert.strictEqual( html, '<p><b>Hello world</b></p>', 'Parse wikitext by string' );
	} );

	QUnit.test( '.parse( Object.toString )', async ( assert ) => {
		server.respondWith( 'POST', /api.php/, [ 200,
			{ 'Content-Type': 'application/json' },
			'{ "parse": { "text": "<p><b>Hello world</b></p>" } }'
		] );

		const input = {
			toString: function () {
				return '\'\'\'Hello world\'\'\'';
			}
		};
		const html = await new mw.Api().parse( input );
		assert.strictEqual( html, '<p><b>Hello world</b></p>', 'Parse wikitext by toString object' );
	} );

	QUnit.test( '.parse( mw.Title )', async ( assert ) => {
		server.respondWith( 'GET', /action=parse.*&page=Earth/, [ 200,
			{ 'Content-Type': 'application/json' },
			'{ "parse": { "text": "<p><b>Earth</b> is a planet.</p>" } }'
		] );

		const html = await new mw.Api().parse( new mw.Title( 'Earth' ) );
		assert.strictEqual( html, '<p><b>Earth</b> is a planet.</p>', 'Parse page by Title object' );
	} );
} );
