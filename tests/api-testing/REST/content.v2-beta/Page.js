'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );
const url = require( 'url' );
const chai = require( 'chai' );
const expect = chai.expect;

const chaiResponseValidator = require( 'chai-openapi-response-validator' ).default;

const pathPrefix = '/content/v2-beta';
const specModule = '/content/v2-beta';

// Parse a URL-ref, which may or may not contain a protocol and host.
// WHATWG URL currently doesn't support partial URLs, see https://github.com/whatwg/url/issues/531
function parseURL( ref ) {
	const urlObj = new url.URL( ref, 'http://fake-host' );
	const urlRec = {
		protocol: urlObj.protocol,
		host: urlObj.host,
		hostname: urlObj.hostname,
		port: urlObj.port,
		pathname: urlObj.pathname,
		search: urlObj.search,
		hash: urlObj.hash
	};

	if ( urlRec.hostname === 'fake-host' ) {
		urlRec.host = '';
		urlRec.hostname = '';
		urlRec.port = '';
	}

	return urlRec;
}

// The content/v2-beta module replaces the suffix based output selection of v1
// (/bare, /with_html) with the "prop" query parameter on a single route. These
// tests mirror the v1 tests in REST/Page.js, but drive the output through
// "prop" instead of through the path.
describe( 'content/v2-beta Page', () => {
	const page = utils.title( 'ContentV2Page_' );
	const pageWithSpaces = page.replace( '_', ' ' );
	const variantPage = utils.title( 'ContentV2Variant' );

	// Create a page (or "agepay") for the pig latin variant test.
	const agepayHash = utils.title( '' ).replace( /\d/g, 'x' ).toLowerCase(); // only lower-case letters.
	const agepay = 'Page' + agepayHash; // will not exist
	const atinlayAgepay = 'Age' + agepayHash + 'pay'; // will exist

	const redirectPage = utils.title( 'ContentV2Redirect ' );
	const redirectedPage = redirectPage.replace( 'Redirect', 'Redirected' );

	let client;
	let mindy;
	let openApiSpec;
	const baseEditText = "''Edit 1'' and '''Edit 2'''";

	before( async () => {
		client = new REST( 'rest.php' );

		mindy = await action.mindy();
		await mindy.edit( page, { text: baseEditText } );
		await mindy.edit( atinlayAgepay, { text: baseEditText } );

		// Setup page with redirects
		await mindy.edit( redirectPage, { text: `Original name is ${ redirectPage }` } );
		const token = await mindy.token();
		await mindy.action( 'move', {
			from: redirectPage,
			to: redirectedPage,
			token
		}, true );

		const specPath = '/specs/v0/module' + specModule;
		const { status, text } = await client.get( specPath );
		assert.deepEqual( status, 200 );

		openApiSpec = JSON.parse( text );
		chai.use( chaiResponseValidator( openApiSpec ) );
	} );

	describe( 'GET /page/{title} with prop', () => {
		it( 'Should return metadata and a link to the HTML when no prop is given', async () => {
			// This is what /page/{title}/bare returns in v1.
			const res = await client.get( `${ pathPrefix }/page/${ page }` );
			const { status, body, text, headers } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.containsAllKeys( body, [ 'latest', 'id', 'key', 'license', 'title', 'content_model', 'html_url' ] );
			assert.doesNotHaveAnyKeys( body, [ 'source', 'html' ] );
			assert.nestedPropertyVal( body, 'content_model', 'wikitext' );
			assert.nestedPropertyVal( body, 'title', pageWithSpaces );
			assert.nestedPropertyVal( body, 'key', utils.dbkey( page ) );

			// The link stays inside this module, it does not point back into v1.
			assert.match(
				body.html_url,
				new RegExp( `${ pathPrefix }/page/${ encodeURIComponent( utils.dbkey( page ) ) }/html$` )
			);
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Should return page source for prop=source', async () => {
			// This is what /page/{title} returns in v1.
			const res = await client.get( `${ pathPrefix }/page/${ page }`, { prop: 'source' } );
			const { status, body, text, headers } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.match( headers.vary, /\bx-restbase-compat\b/ );
			assert.containsAllKeys( body, [ 'latest', 'id', 'key', 'license', 'title', 'content_model', 'source' ] );
			assert.doesNotHaveAnyKeys( body, [ 'html' ] );
			assert.nestedPropertyVal( body, 'content_model', 'wikitext' );
			assert.nestedPropertyVal( body, 'title', pageWithSpaces );
			assert.nestedPropertyVal( body, 'key', utils.dbkey( page ) );
			assert.nestedPropertyVal( body, 'source', baseEditText );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Should return page HTML for prop=html', async () => {
			// This is what /page/{title}/with_html returns in v1. The HTML is
			// embedded, so no html_url link is emitted alongside it.
			const res = await client.get( `${ pathPrefix }/page/${ page }`, { prop: 'html' } );
			const { status, body, text, headers } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.containsAllKeys( body, [ 'latest', 'id', 'key', 'license', 'title', 'content_model', 'html' ] );
			assert.doesNotHaveAnyKeys( body, [ 'source', 'html_url' ] );
			assert.nestedPropertyVal( body, 'title', pageWithSpaces );
			assert.match( body.html, /<html\b/ );
			assert.match( body.html, /Edit \w+<\/b>/ );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Should return both source and HTML for prop=source|html', async () => {
			// Combining properties has no equivalent among the v1 suffixes.
			const res = await client.get( `${ pathPrefix }/page/${ page }`, { prop: 'source|html' } );
			const { status, body, text, headers } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.containsAllKeys( body, [ 'latest', 'id', 'key', 'license', 'title', 'content_model', 'source', 'html' ] );
			assert.doesNotHaveAnyKeys( body, [ 'html_url' ] );
			assert.nestedPropertyVal( body, 'source', baseEditText );
			assert.match( body.html, /<html\b/ );
			assert.match( body.html, /Edit \w+<\/b>/ );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Should vary the ETag by the requested prop', async () => {
			const htmlEtag = ( await client.get( `${ pathPrefix }/page/${ page }`, { prop: 'html' } ) )
				.headers.etag;
			const bothEtag = ( await client.get( `${ pathPrefix }/page/${ page }`, { prop: 'source|html' } ) )
				.headers.etag;

			assert.isOk( htmlEtag, 'etag' );
			assert.notEqual( htmlEtag, bothEtag );
		} );

		it( 'Should return 400 for an unknown prop value', async () => {
			const { status, body, text } = await client.get(
				`${ pathPrefix }/page/${ page }`,
				{ prop: 'bogus' }
			);
			assert.deepEqual( status, 400, text );
			assert.nestedPropertyVal( body, 'errorCode', 'badvalue' );
			assert.nestedPropertyVal( body, 'name', 'prop' );
		} );

		it( 'Should return 404 error for non-existent page', async () => {
			const dummyPageTitle = utils.title( 'DummyPage_' );
			const { status, body } = await client.get(
				`${ pathPrefix }/page/${ dummyPageTitle }`,
				{ prop: 'source' }
			);
			assert.deepEqual( status, 404 );
			// This module declares errorSchemaVersion 2.0, so errors are reported
			// with errorCode/httpCode rather than the v1 type/title/detail shape.
			assert.containsAllKeys( body, [ 'errorCode', 'message', 'httpCode' ] );
			assert.nestedPropertyVal( body, 'errorCode', 'rest-nonexistent-title' );
		} );

		// NOTE: These two currently FAIL with 403, and so do their v1 counterparts
		// in REST/Page.js. PageHandler::run() runs checkAccessPermission() before
		// checkHasContent(), so a title that resolves to no page at all is reported
		// as a permission denial instead of as missing. The assertions below state
		// the contract that v1 shipped; they are not skipped, because a 404 turning
		// into a 403 on /v1/page/{title} is a breaking API change.
		it( 'Should return 404 error for invalid titles', async () => {
			const badTitle = '::X::';
			const { status } = await client.get( `${ pathPrefix }/page/${ badTitle }` );
			assert.deepEqual( status, 404 );
		} );

		it( 'Should return 404 error for special pages', async () => {
			const badTitle = 'Special:Blankpage';
			const { status } = await client.get( `${ pathPrefix }/page/${ badTitle }` );
			assert.deepEqual( status, 404 );
		} );

		it( 'Should return 404 for a system message page, for every prop', async () => {
			// Unlike v1's /page/{title}/with_html, this module does not opt into
			// serving "shadow" content for pages that have no stored content.
			// See T349677 for the pending decision on that behavior.
			const msg = 'MediaWiki:Newpage-desc';
			for ( const prop of [ null, 'source', 'html', 'source|html' ] ) {
				const { status, text } = await client.get(
					`${ pathPrefix }/page/${ msg }`,
					prop ? { prop } : null
				);
				assert.deepEqual( status, 404, `prop=${ prop }: ${ text }` );
			}
		} );

		it( 'Should have appropriate response headers', async () => {
			const preEditResponse = await client.get( `${ pathPrefix }/page/${ page }`, { prop: 'source' } );
			const preEditDate = new Date( preEditResponse.body.latest.timestamp );
			const preEditEtag = preEditResponse.headers.etag;

			await mindy.edit( page, { text: "'''Edit 3'''" } );
			const postEditResponse = await client.get( `${ pathPrefix }/page/${ page }`, { prop: 'source' } );
			const postEditDate = new Date( postEditResponse.body.latest.timestamp );
			const postEditHeaders = postEditResponse.headers;
			const postEditEtag = postEditResponse.headers.etag;

			assert.containsAllKeys( postEditHeaders, [ 'etag' ] );
			assert.deepEqual( postEditHeaders[ 'last-modified' ], postEditDate.toGMTString() );
			assert.match( postEditHeaders[ 'cache-control' ], /\bmax-age=\d/ );
			assert.strictEqual( isNaN( preEditDate.getTime() ), false );
			assert.strictEqual( isNaN( postEditDate.getTime() ), false );
			assert.notEqual( preEditDate, postEditDate );
			assert.notEqual( preEditEtag, postEditEtag );
		} );

		it( 'Should perform variant conversion for prop=html', async () => {
			await mindy.edit( variantPage, { text: '<p>test language conversion</p>' } );
			const { headers, body } = await client.get(
				`${ pathPrefix }/page/${ variantPage }`,
				{ prop: 'html' },
				{ 'accept-language': 'en-x-piglatin' }
			);

			assert.match( body.html, /esttay anguagelay onversioncay/ );
			// Variant conversion must not change the full-document shape.
			assert.match( body.html, /<html\b/ );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.match( headers.vary, /\bAccept-Language\b/i );
			assert.match( headers.etag, /en-x-piglatin/i );
		} );
	} );

	describe( 'GET /page/{title} with prop and x-restbase-compat', () => {
		it( 'Should successfully return restbase-compatible revision meta-data', async () => {
			// Restbase mode overrides the prop selection entirely.
			const { status, body, text, headers } = await client
				.get( `${ pathPrefix }/page/${ page }`, { prop: 'source' } )
				.set( 'x-restbase-compat', 'true' );
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.match( headers.vary, /\bx-restbase-compat\b/ );
			assert.containsAllKeys( body.items[ 0 ], [ 'title', 'page_id', 'rev', 'tid', 'namespace', 'user_id',
				'user_text', 'timestamp', 'comment', 'tags', 'restrictions', 'page_language', 'redirect' ] );

			assert.deepEqual( body.items[ 0 ].title, utils.dbkey( page ) );
			assert.isAbove( body.items[ 0 ].page_id, 0 );
			assert.isAbove( body.items[ 0 ].rev, 0 );
		} );
	} );

	describe( 'GET /page/{title} redirects with prop', () => {
		it( 'Title normalization should return permanent redirect (301) keeping prop', async () => {
			const redirectDbKey = utils.dbkey( redirectPage );
			const { status, text, headers } = await client.get(
				`${ pathPrefix }/page/${ redirectPage }`,
				{ prop: 'source' }
			);
			const { host, search, pathname } = parseURL( headers.location );
			assert.include( search, 'prop=source' );
			assert.notInclude( search, 'redirect=no' );
			assert.deepEqual( host, '' );
			assert.include( pathname, `${ pathPrefix }/page/${ redirectDbKey }` );
			assert.deepEqual( status, 301, text );
		} );

		it( 'A wiki redirect should be reported in the body when HTML is not requested', async () => {
			const redirectPageDbkey = utils.dbkey( redirectPage );
			const redirectedPageDbKey = utils.dbkey( redirectedPage );
			const res = await client.get(
				`${ pathPrefix }/page/${ redirectPageDbkey }`,
				{ prop: 'source' }
			);
			const { status, body: { redirect_target }, text, headers } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );

			// The target stays on this route and keeps the requested prop, so
			// following it returns the same representation of the target page.
			const { pathname, search } = parseURL( redirect_target );
			assert.deepEqual( pathname.endsWith( `${ pathPrefix }/page/${ encodeURIComponent( redirectedPageDbKey ) }` ), true, pathname );
			assert.include( search, 'prop=source' );
			assert.include( search, 'redirect=no' );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'A wiki redirect should be followed (307) when prop=html', async () => {
			const redirectPageDbkey = utils.dbkey( redirectPage );
			const redirectedPageDbkey = utils.dbkey( redirectedPage );
			const { status, text, headers } = await client.get(
				`${ pathPrefix }/page/${ redirectPageDbkey }`,
				{ prop: 'html' }
			);
			const { host, pathname, search } = parseURL( headers.location );
			assert.include( search, 'prop=html' );
			assert.include( search, 'redirect=no' );
			assert.include( pathname, `${ pathPrefix }/page/${ redirectedPageDbkey }` );
			assert.deepEqual( host, '' );
			assert.deepEqual( status, 307, text );
		} );

		it( 'Variant redirects should return temporary redirect (307) when prop=html', async () => {
			const agepayDbkey = utils.dbkey( agepay );
			const atinlayAgepayDbkey = utils.dbkey( atinlayAgepay );
			const { status, text, headers } = await client.get(
				`${ pathPrefix }/page/${ agepayDbkey }`,
				{ prop: 'html' }
			);
			const { host, pathname, search } = parseURL( headers.location );
			assert.include( search, 'redirect=no' );
			assert.deepEqual( host, '' );
			assert.include( pathname, `${ pathPrefix }/page/${ atinlayAgepayDbkey }` );
			assert.deepEqual( status, 307, text );
		} );

		it( 'Should return 404 for a page that only exists as a variant when HTML is not requested', async () => {
			const agepayDbkey = utils.dbkey( agepay );
			const { status } = await client.get(
				`${ pathPrefix }/page/${ agepayDbkey }`,
				{ prop: 'source' }
			);
			assert.deepEqual( status, 404 );
		} );

		it( 'Bypass wiki redirects with query param redirect=no', async () => {
			const redirectPageDbkey = utils.dbkey( redirectPage );
			const redirectedPageDbKey = utils.dbkey( redirectedPage );
			const res = await client.get(
				`${ pathPrefix }/page/${ redirectPageDbkey }`,
				{ prop: 'html', redirect: 'no' }
			);
			const { status, body: { redirect_target, html }, text, headers } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.match( redirect_target, new RegExp( `/page/${ encodeURIComponent( redirectedPageDbKey ) }` ) );
			assert.match( html, /<html\b/ );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Bypass variant redirects with query param redirect=no', async () => {
			const agepayDbkey = utils.dbkey( agepay );
			const { status, headers } = await client.get(
				`${ pathPrefix }/page/${ agepayDbkey }`,
				{ prop: 'html', redirect: 'no' }
			);
			assert.deepEqual( status, 404 );
			// rest-nonexistent-title error object returned instead of content
			assert.match( headers[ 'content-type' ], /^application\/json/ );
		} );
	} );

	describe( 'GET /page/{title}/html', () => {
		it( 'Should successfully return page HTML', async () => {
			const res = await client.get( `${ pathPrefix }/page/${ page }/html` );
			const { status, headers, text } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^text\/html/ );
			assert.match( text, /<html\b/ );
			assert.match( text, /Edit \w+<\/b>/ );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Should ignore prop, which is not a parameter of this route', async () => {
			// Output selection belongs to /page/{title}; the top-level HTML route
			// always returns a bare HTML document.
			const { status, headers, text } = await client.get(
				`${ pathPrefix }/page/${ page }/html`,
				{ prop: 'source' }
			);
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^text\/html/ );
			assert.match( text, /<html\b/ );
		} );

		it( 'Should return 404 error for non-existent page', async () => {
			const dummyPageTitle = utils.title( 'DummyPage_' );
			const { status } = await client.get( `${ pathPrefix }/page/${ dummyPageTitle }/html` );
			assert.deepEqual( status, 404 );
		} );
	} );
} );
