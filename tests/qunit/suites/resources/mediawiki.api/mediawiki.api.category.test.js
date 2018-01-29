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

	QUnit.test( '.isCategory("")', function ( assert ) {
		this.server.respondWith( /titles=$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true}'
		] );
		return new mw.Api().isCategory( '' ).then( function ( response ) {
			assert.equal( response, false );
		} );
	} );

	QUnit.test( '.isCategory("#")', function ( assert ) {
		this.server.respondWith( /titles=%23$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true,"query":{"normalized":[{"fromencoded":false,"from":"#","to":""}]}}'
		] );
		return new mw.Api().isCategory( '#' ).then( function ( response ) {
			assert.equal( response, false );
		} );
	} );

	QUnit.test( '.isCategory("mw:")', function ( assert ) {
		this.server.respondWith( /titles=mw%3A$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true,"query":{"interwiki":[{"title":"mw:","iw":"mw"}]}}'
		] );
		return new mw.Api().isCategory( 'mw:' ).then( function ( response ) {
			assert.equal( response, false );
		} );
	} );

	QUnit.test( '.isCategory("|")', function ( assert ) {
		this.server.respondWith( /titles=%1F%7C$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true,"query":{"pages":[{"title":"|","invalidreason":"The requested page title contains invalid characters: \\"|\\".","invalid":true}]}}'
		] );
		return new mw.Api().isCategory( '|' ).then( function ( response ) {
			assert.equal( response, false );
		} );
	} );

	QUnit.test( '.getCategories("")', function ( assert ) {
		this.server.respondWith( /titles=$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true}'
		] );
		return new mw.Api().getCategories( '' ).then( function ( response ) {
			assert.equal( response, false );
		} );
	} );

	QUnit.test( '.getCategories("#")', function ( assert ) {
		this.server.respondWith( /titles=%23$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true,"query":{"normalized":[{"fromencoded":false,"from":"#","to":""}]}}'
		] );
		return new mw.Api().getCategories( '#' ).then( function ( response ) {
			assert.equal( response, false );
		} );
	} );

	QUnit.test( '.getCategories("mw:")', function ( assert ) {
		this.server.respondWith( /titles=mw%3A$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true,"query":{"interwiki":[{"title":"mw:","iw":"mw"}]}}'
		] );
		return new mw.Api().getCategories( 'mw:' ).then( function ( response ) {
			assert.equal( response, false );
		} );
	} );

	QUnit.test( '.getCategories("|")', function ( assert ) {
		this.server.respondWith( /titles=%1F%7C$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true,"query":{"pages":[{"title":"|","invalidreason":"The requested page title contains invalid characters: \\"|\\".","invalid":true}]}}'
		] );
		return new mw.Api().getCategories( '|' ).then( function ( response ) {
			assert.equal( response, false );
		} );
	} );

}( mediaWiki ) );
