( function ( mw ) {
	QUnit.module( 'mediawiki.api.category', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
			this.server.respondImmediately = true;
		}
	} ) );

	QUnit.test( '.getCategoriesByPrefix()', function ( assert ) {
		this.server.respondWith( [ 200, { 'Content-Type': 'application/json' },
			'{ "query": { "allpages": [ ' +
				'{ "title": "Category:Food" },' +
				'{ "title": "Category:Fool Supermarine S.6" },' +
				'{ "title": "Category:Fools" }' +
				'] } }'
		] );

		return new mw.Api().getCategoriesByPrefix( 'Foo' ).then( function ( matches ) {
			assert.deepEqual(
				matches,
				[ 'Food', 'Fool Supermarine S.6', 'Fools' ]
			);
		} );
	} );
}( mediaWiki ) );
