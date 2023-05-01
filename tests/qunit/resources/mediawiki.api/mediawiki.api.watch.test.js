QUnit.module( 'mediawiki.api.watch', ( hooks ) => {
	let server;
	hooks.beforeEach( function () {
		server = this.sandbox.useFakeServer();
		server.respondImmediately = true;
	} );

	QUnit.test( '.watch( string )', async ( assert ) => {
		server.respond( function ( req ) {
			// Match POST requestBody
			if ( /action=watch.*&titles=Foo(&|$)/.test( req.requestBody ) ) {
				req.respond( 200, { 'Content-Type': 'application/json' },
					'{ "watch": [ { "title": "Foo", "watched": true, "message": "<b>Added</b>" } ] }'
				);
			}
		} );

		const item = await new mw.Api().watch( 'Foo' );
		assert.strictEqual( item.title, 'Foo' );
	} );

	// Ensure we don't mistake a single item array for a single item and vice versa.
	// The query parameter in request is the same either way (separated by pipe).
	QUnit.test( '.watch( Array ) - single', async ( assert ) => {
		server.respond( function ( req ) {
			// Match POST requestBody
			if ( /action=watch.*&titles=Foo(&|$)/.test( req.requestBody ) ) {
				req.respond( 200, { 'Content-Type': 'application/json' },
					'{ "watch": [ { "title": "Foo", "watched": true, "message": "<b>Added</b>" } ] }'
				);
			}
		} );

		const items = await new mw.Api().watch( [ 'Foo' ] );
		assert.strictEqual( items[ 0 ].title, 'Foo' );
	} );

	QUnit.test( '.watch( Array ) - multi', async ( assert ) => {
		server.respond( function ( req ) {
			// Match POST requestBody
			if ( /action=watch.*&titles=Foo%7CBar/.test( req.requestBody ) ) {
				req.respond( 200, { 'Content-Type': 'application/json' },
					'{ "watch": [ ' +
						'{ "title": "Foo", "watched": true, "message": "<b>Added</b>" },' +
						'{ "title": "Bar", "watched": true, "message": "<b>Added</b>" }' +
						'] }'
				);
			}
		} );

		const items = await new mw.Api().watch( [ 'Foo', 'Bar' ] );
		assert.strictEqual( items[ 0 ].title, 'Foo' );
		assert.strictEqual( items[ 1 ].title, 'Bar' );
	} );
} );
