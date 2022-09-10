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
			const { status, body, text } = await client.get( `/page/${page}` );
			assert.deepEqual( status, 200, text );
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

	describe( 'GET /page/{title}/bare', () => {
		it( 'Should successfully return page bare', async () => {
			const { status, body, text } = await client.get( `/page/${page}/bare` );
			assert.deepEqual( status, 200, text );
			assert.containsAllKeys( body, [ 'latest', 'id', 'key', 'license', 'title', 'content_model', 'html_url' ] );
			assert.nestedPropertyVal( body, 'content_model', 'wikitext' );
			assert.nestedPropertyVal( body, 'title', page );
			assert.nestedPropertyVal( body, 'key', utils.dbkey( page ) );
			assert.match( body.html_url, new RegExp( `/page/${encodeURIComponent( page )}/html$` ) );
		} );
		it( 'Should return 404 error for non-existent page', async () => {
			const dummyPageTitle = utils.title( 'DummyPage_' );
			const { status } = await client.get( `/page/${dummyPageTitle}/bare` );
			assert.deepEqual( status, 404 );
		} );
		it( 'Should have appropriate response headers', async () => {
			const preEditResponse = await client.get( `/page/${page}/bare` );
			const preEditDate = new Date( preEditResponse.body.latest.timestamp );
			const preEditEtag = preEditResponse.headers.etag;

			await anon.edit( page, { text: "'''Edit 4'''" } );
			const postEditResponse = await client.get( `/page/${page}/bare` );
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

	describe( 'GET /page/{title}/html', () => {
		it( 'Should successfully return page HTML', async () => {
			const { status, headers, text } = await client.get( `/page/${page}/html` );
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^text\/html/ );
			assert.match( text, /<html / );
			assert.match( text, /Edit \w+<\/b>/ );
		} );
		it( 'Should return 404 error for non-existent page', async () => {
			const dummyPageTitle = utils.title( 'DummyPage_' );
			const { status } = await client.get( `/page/${dummyPageTitle}/html` );
			assert.deepEqual( status, 404 );
		} );
		it( 'Should have appropriate response headers', async () => {
			const preEditResponse = await client.get( `/page/${page}/html` );
			const preEditDate = new Date( preEditResponse.headers[ 'last-modified' ] );
			const preEditEtag = preEditResponse.headers.etag;

			await anon.edit( page, { text: "'''Edit XYZ'''" } );
			const postEditResponse = await client.get( `/page/${page}/html` );
			const postEditDate = new Date( postEditResponse.headers[ 'last-modified' ] );
			const postEditHeaders = postEditResponse.headers;
			const postEditEtag = postEditResponse.headers.etag;

			assert.containsAllKeys( postEditHeaders, [ 'etag', 'cache-control', 'last-modified' ] );
			assert.match( postEditHeaders[ 'cache-control' ], /^max-age=\d/ );
			assert.strictEqual( isNaN( preEditDate.getTime() ), false );
			assert.strictEqual( isNaN( postEditDate.getTime() ), false );
			assert.notEqual( preEditDate, postEditDate );
			assert.notEqual( preEditEtag, postEditEtag );
			assert.match( postEditHeaders.etag, /^".*"$/, 'ETag must be present and not marked weak' );
		} );
	} );

	describe( 'GET /page/{title}/with_html', () => {
		it( 'Should successfully return page HTML and metadata for Wikitext page', async () => {
			const { status, body, text } = await client.get( `/page/${page}/with_html` );
			assert.deepEqual( status, 200, text );
			assert.containsAllKeys( body, [ 'latest', 'id', 'key', 'license', 'title', 'content_model', 'html' ] );
			assert.nestedPropertyVal( body, 'content_model', 'wikitext' );
			assert.nestedPropertyVal( body, 'title', page );
			assert.nestedPropertyVal( body, 'key', utils.dbkey( page ) );
			assert.match( body.html, /<html / );
			assert.match( body.html, /Edit \w+<\/b>/ );
		} );
		it( 'Should return 404 error for non-existent page', async () => {
			const dummyPageTitle = utils.title( 'DummyPage_' );
			const { status } = await client.get( `/page/${dummyPageTitle}/with_html` );
			assert.deepEqual( status, 404 );
		} );
		it( 'Should have appropriate response headers', async () => {
			const preEditResponse = await client.get( `/page/${page}/with_html` );
			const preEditRevDate = new Date( preEditResponse.body.latest.timestamp );
			const preEditEtag = preEditResponse.headers.etag;

			await anon.edit( page, { text: "'''Edit ABCD'''" } );
			const postEditResponse = await client.get( `/page/${page}/with_html` );
			const postEditRevDate = new Date( postEditResponse.body.latest.timestamp );
			const postEditHeaders = postEditResponse.headers;
			const postEditEtag = postEditResponse.headers.etag;

			assert.containsAllKeys( postEditHeaders, [ 'etag', 'last-modified' ] );
			const postEditHeaderDate = new Date( postEditHeaders[ 'last-modified' ] );

			// The last-modified date is the render timestamp, which may be newer than the revision
			assert.strictEqual( postEditRevDate.valueOf() <= postEditHeaderDate.valueOf(), true );
			assert.match( postEditHeaders[ 'cache-control' ], /^max-age=\d/ );
			assert.strictEqual( isNaN( preEditRevDate.getTime() ), false );
			assert.strictEqual( isNaN( postEditRevDate.getTime() ), false );
			assert.notEqual( preEditRevDate, postEditRevDate );
			assert.notEqual( preEditEtag, postEditEtag );
		} );
	} );
} );
