'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );
const chai = require( 'chai' );
const expect = chai.expect;

const chaiResponseValidator = require( 'chai-openapi-response-validator' ).default;

const pathPrefix = '/content/v2-beta';
const specModule = '/content/v2-beta';

// The content/v2-beta module replaces the suffix based output selection of v1
// (/bare, /with_html) with the "prop" query parameter on a single route. These
// tests mirror the v1 tests in REST/Revision.js, but drive the output through
// "prop" instead of through the path.
describe( 'content/v2-beta Revision', () => {
	const page = utils.title( 'ContentV2Revision' );
	let client;
	let mindy;
	let newrevid, pageid, param_summary, openApiSpec;

	before( async () => {
		client = new REST( 'rest.php' );
		mindy = await action.mindy();

		const resp = await mindy.edit( page, {
			text: 'Hello World',
			summary: 'creating page'
		} );
		( { newrevid, pageid, param_summary } = resp );

		const specPath = '/specs/v0/module' + specModule;
		const { status, text } = await client.get( specPath );
		assert.deepEqual( status, 200 );

		openApiSpec = JSON.parse( text );
		chai.use( chaiResponseValidator( openApiSpec ) );
	} );

	describe( 'GET /revision/{id} with prop', () => {
		it( 'Should return metadata and a link to the HTML when no prop is given', async () => {
			// This is what /revision/{id}/bare returns in v1.
			const res = await client.get( `${ pathPrefix }/revision/${ newrevid }` );
			const { status, body, text, headers } = res;

			assert.strictEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.strictEqual( body.id, newrevid );
			assert.strictEqual( body.minor, false );
			assert.deepEqual( body.page, { id: pageid, title: page, key: utils.dbkey( page ) } );
			assert.nestedPropertyVal( body, 'user.name', mindy.username );
			assert.strictEqual( body.comment, param_summary );
			assert.nestedProperty( body, 'html_url' );
			assert.doesNotHaveAnyKeys( body, [ 'source', 'html' ] );
			assert.isOk( headers.etag, 'etag' );
			assert.equal( Date.parse( body.timestamp ), Date.parse( headers[ 'last-modified' ] ) );

			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Should return revision source for prop=source', async () => {
			// This is what /revision/{id} returns in v1.
			const res = await client.get( `${ pathPrefix }/revision/${ newrevid }`, { prop: 'source' } );
			const { status, body, text, headers } = res;

			assert.strictEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.match( headers.vary, /\bx-restbase-compat\b/ );
			assert.strictEqual( body.id, newrevid );
			assert.deepEqual( body.page, { id: pageid, title: page, key: utils.dbkey( page ) } );
			assert.nestedPropertyVal( body, 'source', 'Hello World' );
			assert.doesNotHaveAnyKeys( body, [ 'html' ] );
			assert.isOk( headers.etag, 'etag' );
			assert.equal( Date.parse( body.timestamp ), Date.parse( headers[ 'last-modified' ] ) );

			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Should return revision HTML for prop=html', async () => {
			// This is what /revision/{id}/with_html returns in v1.
			const res = await client.get( `${ pathPrefix }/revision/${ newrevid }`, { prop: 'html' } );
			const { status, body, text, headers } = res;

			assert.strictEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.strictEqual( body.id, newrevid );
			assert.deepEqual( body.page, { id: pageid, title: page, key: utils.dbkey( page ) } );
			assert.match( body.html, /<html\b/ );
			assert.match( body.html, /Hello World/ );
			assert.doesNotHaveAnyKeys( body, [ 'source', 'html_url' ] );
			assert.isOk( headers.etag, 'etag' );

			// The last-modified date is the render timestamp, which may be newer than the revision
			const headerDate = Date.parse( headers[ 'last-modified' ] );
			const revDate = Date.parse( body.timestamp );
			assert.strictEqual( revDate.valueOf() <= headerDate.valueOf(), true );

			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Should return both source and HTML for prop=source|html', async () => {
			// Combining properties has no equivalent among the v1 suffixes.
			const res = await client.get( `${ pathPrefix }/revision/${ newrevid }`, { prop: 'source|html' } );
			const { status, body, text, headers } = res;

			assert.strictEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.nestedPropertyVal( body, 'source', 'Hello World' );
			assert.match( body.html, /<html\b/ );
			assert.match( body.html, /Hello World/ );
			assert.doesNotHaveAnyKeys( body, [ 'html_url' ] );

			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Should vary the ETag by the requested prop', async () => {
			const htmlEtag = ( await client.get( `${ pathPrefix }/revision/${ newrevid }`, { prop: 'html' } ) )
				.headers.etag;
			const bothEtag = ( await client.get( `${ pathPrefix }/revision/${ newrevid }`, { prop: 'source|html' } ) )
				.headers.etag;

			assert.isOk( htmlEtag, 'etag' );
			assert.notEqual( htmlEtag, bothEtag );
		} );

		it( 'Should return 400 for an unknown prop value', async () => {
			const { status, body, text } = await client.get(
				`${ pathPrefix }/revision/${ newrevid }`,
				{ prop: 'bogus' }
			);
			assert.deepEqual( status, 400, text );
			assert.nestedPropertyVal( body, 'errorCode', 'badvalue' );
			assert.nestedPropertyVal( body, 'name', 'prop' );
		} );

		it( 'Should return 404 for revision that does not exist', async () => {
			const { status, body } = await client.get(
				`${ pathPrefix }/revision/99999999`,
				{ prop: 'source' }
			);
			assert.strictEqual( status, 404 );
			assert.containsAllKeys( body, [ 'errorCode', 'message', 'httpCode' ] );
		} );

		it( 'Should perform variant conversion for prop=html', async () => {
			const { headers, body, text } = await client.get(
				`${ pathPrefix }/revision/${ newrevid }`,
				{ prop: 'html' },
				{ 'accept-language': 'en-x-piglatin' }
			);

			assert.match( text, /Ellohay/ );
			assert.match( text, /Orldway/ );
			// Variant conversion must not change the full-document shape.
			assert.match( body.html, /<html\b/ );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.match( headers.vary, /\bAccept-Language\b/i );
			assert.match( headers.etag, /en-x-piglatin/i );
		} );
	} );

	describe( 'GET /revision/{id} with prop and x-restbase-compat', () => {
		it( 'Should successfully return restbase-compatible revision meta-data', async () => {
			// Restbase mode overrides the prop selection entirely.
			const { status, body, text, headers } = await client
				.get( `${ pathPrefix }/revision/${ newrevid }`, { prop: 'source' } )
				.set( 'x-restbase-compat', 'true' );
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.match( headers.vary, /\bx-restbase-compat\b/ );
			assert.containsAllKeys( body.items[ 0 ], [ 'title', 'page_id', 'rev', 'tid', 'namespace', 'user_id',
				'user_text', 'timestamp', 'comment', 'tags', 'restrictions' ] );

			assert.deepEqual( body.items[ 0 ].title, utils.dbkey( page ) );
			assert.deepEqual( body.items[ 0 ].page_id, pageid );
			assert.deepEqual( body.items[ 0 ].rev, newrevid );
		} );
	} );

	describe( 'GET /revision/{id}/html', () => {
		it( 'Should successfully return revision HTML', async () => {
			const res = await client.get( `${ pathPrefix }/revision/${ newrevid }/html` );
			const { status, text, headers } = res;

			assert.strictEqual( status, 200, text );
			assert.containsAllKeys( headers, [ 'etag', 'cache-control', 'last-modified', 'content-type' ] );
			assert.match( headers[ 'content-type' ], /^text\/html/ );
			assert.match( text, /<html\b/ );
			assert.match( text, /Hello World/ );
			assert.match( headers.etag, /^".*"$/, 'ETag must be present and not marked weak' );
		} );

		it( 'Should return 404 for revision that does not exist', async () => {
			const { status } = await client.get( `${ pathPrefix }/revision/99999999/html` );
			assert.strictEqual( status, 404 );
		} );
	} );
} );
