( function ( mw ) {
	QUnit.module( 'mediawiki.api.category', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
		}
	} ) );

	QUnit.test( '.getCategoriesByPrefix()', function ( assert ) {
		QUnit.expect( 1 );

		var api = new mw.Api();

		api.getCategoriesByPrefix( 'Foo' ).done( function ( matches ) {
			assert.deepEqual(
				matches,
				[ 'Food', 'Fool Supermarine S.6', 'Fools' ]
			);
		} );

		this.server.respond( function ( req ) {
			req.respond( 200, { 'Content-Type': 'application/json' },
				'{ "query": { "allpages": [ ' +
					'{ "title": "Category:Food" },' +
					'{ "title": "Category:Fool Supermarine S.6" },' +
					'{ "title": "Category:Fools" }' +
					'] } }'
			);
		} );
	} );
}( mediaWiki ) );
