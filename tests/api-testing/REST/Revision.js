'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );

describe( 'Revision', () => {
	const client = new REST();
	const page = utils.title( 'Revision' );
	let mindy;
	let newrevid, pageid, param_summary;

	before( async () => {
		mindy = await action.mindy();

		const resp = await mindy.edit( page, {
			text: 'Hello World',
			summary: 'creating page'
		} );
		( { newrevid, pageid, param_summary } = resp );
	} );

	describe( 'GET /revision/{from}/compare/{to}', () => {
		const pageOne = utils.title( 'RevisionCompare_1' );
		const pageTwo = utils.title( 'RevisionCompare_2' );
		const nonExistentRevId = 999;
		const validRevId = 1;
		const invalidRevId = '#';

		it( 'should return 400 if revision id is not an integer', async () => {
			const { status } = await client.get( `/revision/${validRevId}/compare/${invalidRevId}` );
			assert.equal( status, 400 );
		} );

		it( 'should successfully get diff between 2 valid revisions', async () => {
			// XXX: this test requires php-wikidiff2 1.10 or later to be installed
			const { newrevid: revId1 } = await mindy.edit( pageOne, { text: 'Mindy Edit 1' } );
			const { newrevid: revId2 } = await mindy.edit( pageOne, { text: 'Mindy Edit 2' } );
			const response = await client.get( `/revision/${revId1}/compare/${revId2}` );
			const { status, body, text } = response;
			assert.strictEqual( status, 200, text );
			assert.nestedPropertyVal( body, 'from.id', revId1 );
			assert.nestedPropertyVal( body, 'to.id', revId2 );
			assert.nestedProperty( body, 'diff' );
			assert.isArray( body.diff );
		} );

		it( 'should return 404 for revision that does not exist', async () => {
			const { status } = await client.get( `/revision/${validRevId}/compare/${nonExistentRevId}` );
			assert.strictEqual( status, 404 );
		} );

		it( 'should return 400 if revision ids belong to different pages', async () => {
			const { newrevid: pageOneRev } = await mindy.edit( pageOne, { text: 'Page 1 edit' } );
			const { newrevid: pageTwoRev } = await mindy.edit( pageTwo, { text: 'Page 2 edit' } );
			const { status } = await client.get( `/revision/${pageOneRev}/compare/${pageTwoRev}` );
			assert.strictEqual( status, 400 );
		} );
	} );

	describe( 'GET /revision/{id}', () => {
		it( 'should successfully get source and metadata for revision', async () => {
			const { status, body, text, headers } = await client.get( `/revision/${newrevid}` );

			assert.strictEqual( status, 200, text );
			assert.strictEqual( body.id, newrevid );
			assert.strictEqual( body.minor, false );
			assert.deepEqual( body.page, { id: pageid, title: page, key: utils.dbkey( page ) } );
			assert.nestedProperty( body, 'timestamp' );
			assert.nestedPropertyVal( body, 'user.name', mindy.username );
			assert.strictEqual( body.comment, param_summary );
			assert.isOk( headers.etag, 'etag' );
			assert.equal( Date.parse( body.timestamp ), Date.parse( headers[ 'last-modified' ] ) );
			assert.nestedPropertyVal( body, 'source', 'Hello World' );
		} );

		it( 'should return 404 for revision that does not exist', async () => {
			const { status } = await client.get( '/revision/99999999' );

			assert.strictEqual( status, 404 );
		} );
	} );

	describe( 'GET /revision/{id}/bare', () => {
		it( 'should successfully get information about revision', async () => {
			const { status, body, text, headers } = await client.get( `/revision/${newrevid}/bare` );

			assert.strictEqual( status, 200, text );
			assert.strictEqual( body.id, newrevid );
			assert.strictEqual( body.minor, false );
			assert.deepEqual( body.page, { id: pageid, title: page, key: utils.dbkey( page ) } );
			assert.nestedProperty( body, 'timestamp' );
			assert.nestedPropertyVal( body, 'user.name', mindy.username );
			assert.strictEqual( body.comment, param_summary );
			assert.isOk( headers.etag, 'etag' );
			assert.equal( Date.parse( body.timestamp ), Date.parse( headers[ 'last-modified' ] ) );
			assert.nestedProperty( body, 'html_url' );
		} );

		it( 'should return 404 for revision that does not exist', async () => {
			const { status } = await client.get( '/revision/99999999/bare' );

			assert.strictEqual( status, 404 );
		} );
	} );

	describe( 'GET /revision/{id}/with_html', () => {
		it( 'should successfully get metadata and HTML of revision', async () => {
			const { status, body, text, headers } = await client.get( `/revision/${newrevid}/with_html` );

			assert.strictEqual( status, 200, text );
			assert.strictEqual( body.id, newrevid );
			assert.strictEqual( body.minor, false );
			assert.deepEqual( body.page, { id: pageid, title: page, key: utils.dbkey( page ) } );
			assert.nestedProperty( body, 'timestamp' );
			assert.nestedPropertyVal( body, 'user.name', mindy.username );
			assert.strictEqual( body.comment, param_summary );
			assert.isOk( headers.etag, 'etag' );

			assert.nestedProperty( body, 'html' );
			assert.match( body.html, /<html / );
			assert.match( body.html, /Hello World/ );

			// The last-modified date is the render timestamp, which may be newer than the revision
			const headerDate = Date.parse( headers[ 'last-modified' ] );
			const revDate = Date.parse( body.timestamp );
			assert.strictEqual( revDate.valueOf() <= headerDate.valueOf(), true );
		} );

		it( 'should return 404 for revision that does not exist', async () => {
			const { status } = await client.get( '/revision/99999999/with_html' );

			assert.strictEqual( status, 404 );
		} );

		it( 'should perform variant conversion', async () => {
			const { headers, text } = await client.get( `/revision/${newrevid}/with_html`, null, {
				'accept-language': 'en-x-piglatin'
			} );

			assert.match( text, /Ellohay/ );
			assert.match( text, /Orldway/ );
			assert.match( headers.vary, /\bAccept-Language\b/i );
			assert.match( headers.etag, /en-x-piglatin/i );
			// Since with_html returns JSON, content language is not set
			// but if its set, we expect it to be set correctly.
			const contentLanguageHeader = headers[ 'content-language' ];
			if ( contentLanguageHeader ) {
				assert.match( headers[ 'content-language' ], /en-x-piglatin/i );
			}
		} );
	} );

	describe( 'GET /revision/{id}/html', () => {
		it( 'should successfully get HTML of revision', async () => {
			const { status, text, headers } = await client.get( `/revision/${newrevid}/html` );

			assert.strictEqual( status, 200, text );
			assert.containsAllKeys( headers, [ 'etag', 'cache-control', 'last-modified', 'content-type' ] );
			assert.match( headers[ 'content-type' ], /^text\/html/ );

			assert.match( text, /<html / );
			assert.match( text, /Hello World/ );
			assert.match( headers.etag, /^".*"$/, 'ETag must be present and not marked weak' );
		} );

		it( 'should return 404 for revision that does not exist', async () => {
			const { status } = await client.get( '/revision/99999999/html' );

			assert.strictEqual( status, 404 );
		} );

		it( 'should perform variant conversion', async () => {
			const { headers, text } = await client.get( `/revision/${newrevid}/html`, null, {
				'accept-language': 'en-x-piglatin'
			} );

			assert.match( text, /Ellohay/ );
			assert.match( text, /Orldway/ );
			assert.match( headers.vary, /\bAccept-Language\b/i );
			assert.match( headers[ 'content-language' ], /en-x-piglatin/i );
			assert.match( headers.etag, /en-x-piglatin/i );
		} );
	} );

} );
