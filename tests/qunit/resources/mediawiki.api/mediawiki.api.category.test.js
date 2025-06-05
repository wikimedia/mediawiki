QUnit.module( 'mediawiki.api.category', ( hooks ) => {
	let server;
	hooks.beforeEach( function () {
		server = this.sandbox.useFakeServer();
		server.respondImmediately = true;
	} );

	QUnit.test( '.getCategoriesByPrefix()', async ( assert ) => {
		server.respondWith( [ 200, { 'Content-Type': 'application/json' },
			'{ "query": { "allpages": [ ' +
				'{ "title": "Category:Food" },' +
				'{ "title": "Category:Fool Supermarine S.6" },' +
				'{ "title": "Category:Fools" }' +
				'] } }'
		] );
		const matches = await new mw.Api().getCategoriesByPrefix( 'Foo' );
		assert.deepEqual(
			matches,
			[ 'Food', 'Fool Supermarine S.6', 'Fools' ]
		);
	} );

	QUnit.test( '.isCategory("")', async ( assert ) => {
		server.respondWith( /titles=$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true}'
		] );
		const response = await new mw.Api().isCategory( '' );
		assert.false( response );
	} );

	QUnit.test( '.isCategory("#")', async ( assert ) => {
		server.respondWith( /titles=%23$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true,"query":{"normalized":[{"fromencoded":false,"from":"#","to":""}]}}'
		] );
		const response = await new mw.Api().isCategory( '#' );
		assert.false( response );
	} );

	QUnit.test( '.isCategory("mw:")', async ( assert ) => {
		server.respondWith( /titles=mw%3A$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true,"query":{"interwiki":[{"title":"mw:","iw":"mw"}]}}'
		] );
		const response = await new mw.Api().isCategory( 'mw:' );
		assert.false( response );
	} );

	QUnit.test( '.isCategory("|")', async ( assert ) => {
		server.respondWith( /titles=%1F%7C$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true,"query":{"pages":[{"title":"|","invalidreason":"The requested page title contains invalid characters: \\"|\\".","invalid":true}]}}'
		] );
		const response = await new mw.Api().isCategory( '|' );
		assert.false( response );
	} );

	QUnit.test( '.getCategories("")', async ( assert ) => {
		server.respondWith( /titles=$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true}'
		] );
		const response = await new mw.Api().getCategories( '' );
		assert.false( response );
	} );

	QUnit.test( '.getCategories("#")', async ( assert ) => {
		server.respondWith( /titles=%23$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true,"query":{"normalized":[{"fromencoded":false,"from":"#","to":""}]}}'
		] );
		const response = await new mw.Api().getCategories( '#' );
		assert.false( response );
	} );

	QUnit.test( '.getCategories("mw:")', async ( assert ) => {
		server.respondWith( /titles=mw%3A$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true,"query":{"interwiki":[{"title":"mw:","iw":"mw"}]}}'
		] );
		const response = await new mw.Api().getCategories( 'mw:' );
		assert.false( response );
	} );

	QUnit.test( '.getCategories("|")', async ( assert ) => {
		server.respondWith( /titles=%1F%7C$/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{"batchcomplete":true,"query":{"pages":[{"title":"|","invalidreason":"The requested page title contains invalid characters: \\"|\\".","invalid":true}]}}'
		] );
		const response = await new mw.Api().getCategories( '|' );
		assert.false( response );
	} );
} );
