'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );

describe( 'Page Source', () => {
	const page = utils.title( 'PageSource ' );
	const client = new REST();
	const anon = action.getAnon();

	before( async () => {
		await anon.edit( page, { text: "''Edit 1'' and '''Edit 2'''" } );
	} );

	describe( 'GET /page/{title}', () => {
		it( 'Should successfully return page source and metadata for Wikitext page', async () => {
			const { status, body } = await client.get( `/page/${page}` );
			assert.deepEqual( status, 200 );
			assert.containsAllKeys( body, [ 'latest', 'id', 'key', 'license', 'title', 'content_model', 'source' ] );
			assert.nestedPropertyVal( body, 'content_model', 'wikitext' );
			assert.nestedPropertyVal( body, 'title', page );
			assert.nestedPropertyVal( body, 'key', utils.dbkey( page ) );
			assert.nestedPropertyVal( body, 'source', "''Edit 1'' and '''Edit 2'''" );
		} );
		it( 'Should return 404 error for non-existent page', async () => {
			const dummyPageTitle = utils.title( 'DummyPage_' );
			const { status } = await client.get( `/page/${dummyPageTitle}` );
			assert.deepEqual( status, 404 );
		} );
		it( 'Should have appropriate response headers', async () => {
			const preEditResponse = await client.get( `/page/${page}` );
			const preEditDate = new Date( preEditResponse.body.latest.timestamp );
			const preEditEtag = preEditResponse.headers.etag;

			await anon.edit( page, { text: "'''Edit 3'''" } );
			const postEditResponse = await client.get( `/page/${page}` );
			const postEditDate = new Date( postEditResponse.body.latest.timestamp );
			const postEditHeaders = postEditResponse.headers;
			const postEditEtag = postEditResponse.headers.etag;

			assert.containsAllKeys( postEditHeaders, [ 'etag' ] );
			assert.deepEqual( postEditHeaders[ 'last-modified' ], postEditDate.toGMTString() );
			assert.match( postEditHeaders[ 'cache-control' ], /^max-age=\d/ );
			assert.strictEqual( isNaN( preEditDate.getTime() ), false );
			assert.strictEqual( isNaN( postEditDate.getTime() ), false );
			assert.notEqual( preEditDate, postEditDate );
			assert.notEqual( preEditEtag, postEditEtag );
		} );
	} );
} );
