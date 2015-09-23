( function ( mw ) {
	QUnit.module( 'mediawiki.api.watch', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
		}
	} ) );

	QUnit.test( '.watch()', function ( assert ) {
		QUnit.expect( 4 );

		var api = new mw.Api();

		// Ensure we don't mistake a single item array for a single item and vice versa.
		// The query parameter in request is the same either way (separated by pipe).
		api.watch( 'Foo' ).done( function ( item ) {
			assert.equal( item.title, 'Foo' );
		} );

		api.watch( [ 'Foo' ] ).done( function ( items ) {
			assert.equal( items[ 0 ].title, 'Foo' );
		} );

		api.watch( [ 'Foo', 'Bar' ] ).done( function ( items ) {
			assert.equal( items[ 0 ].title, 'Foo' );
			assert.equal( items[ 1 ].title, 'Bar' );
		} );

		// Requests are POST, match requestBody instead of url
		this.server.respond( function ( req ) {
			if ( /action=watch.*&titles=Foo(&|$)/.test( req.requestBody ) ) {
				req.respond( 200, { 'Content-Type': 'application/json' },
					'{ "watch": [ { "title": "Foo", "watched": true, "message": "<b>Added</b>" } ] }'
				);
			}

			if ( /action=watch.*&titles=Foo%7CBar/.test( req.requestBody ) ) {
				req.respond( 200, { 'Content-Type': 'application/json' },
					'{ "watch": [ ' +
						'{ "title": "Foo", "watched": true, "message": "<b>Added</b>" },' +
						'{ "title": "Bar", "watched": true, "message": "<b>Added</b>" }' +
						'] }'
				);
			}
		} );
	} );
}( mediaWiki ) );
