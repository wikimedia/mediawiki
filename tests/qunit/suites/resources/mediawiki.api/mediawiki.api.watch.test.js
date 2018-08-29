( function () {
	QUnit.module( 'mediawiki.api.watch', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
			this.server.respondImmediately = true;
		}
	} ) );

	QUnit.test( '.watch( string )', function ( assert ) {
		this.server.respond( function ( req ) {
			// Match POST requestBody
			if ( /action=watch.*&titles=Foo(&|$)/.test( req.requestBody ) ) {
				req.respond( 200, { 'Content-Type': 'application/json' },
					'{ "watch": [ { "title": "Foo", "watched": true, "message": "<b>Added</b>" } ] }'
				);
			}
		} );

		return new mw.Api().watch( 'Foo' ).done( function ( item ) {
			assert.strictEqual( item.title, 'Foo' );
		} );
	} );

	// Ensure we don't mistake a single item array for a single item and vice versa.
	// The query parameter in request is the same either way (separated by pipe).
	QUnit.test( '.watch( Array ) - single', function ( assert ) {
		this.server.respond( function ( req ) {
			// Match POST requestBody
			if ( /action=watch.*&titles=Foo(&|$)/.test( req.requestBody ) ) {
				req.respond( 200, { 'Content-Type': 'application/json' },
					'{ "watch": [ { "title": "Foo", "watched": true, "message": "<b>Added</b>" } ] }'
				);
			}
		} );

		return new mw.Api().watch( [ 'Foo' ] ).done( function ( items ) {
			assert.strictEqual( items[ 0 ].title, 'Foo' );
		} );
	} );

	QUnit.test( '.watch( Array ) - multi', function ( assert ) {
		this.server.respond( function ( req ) {
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

		return new mw.Api().watch( [ 'Foo', 'Bar' ] ).done( function ( items ) {
			assert.strictEqual( items[ 0 ].title, 'Foo' );
			assert.strictEqual( items[ 1 ].title, 'Bar' );
		} );
	} );

}() );
