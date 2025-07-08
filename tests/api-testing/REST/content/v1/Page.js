'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );
const url = require( 'url' );
const chai = require( 'chai' );
const expect = chai.expect;

const chaiResponseValidator = require( 'chai-openapi-response-validator' ).default;

let pathPrefix = '/content/v1';
let specModule = '/content/v1';

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

describe( 'Page Source', () => {
	const page = utils.title( 'PageSource_' );
	const pageWithSpaces = page.replace( '_', ' ' );
	const variantPage = utils.title( 'PageSourceVariant' );
	const fallbackVariantPage = 'MediaWiki:Tog-underline/sh-latn';

	// Create a page (or "agepay") for the pig latin variant test.
	const agepayHash = utils.title( '' ).replace( /\d/g, 'x' ).toLowerCase(); // only lower-case letters.
	const agepay = 'Page' + agepayHash; // will not exist
	const atinlayAgepay = 'Age' + agepayHash + 'pay'; // will exist

	const redirectPage = utils.title( 'Redirect ' );
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

	describe( 'GET /page/{title}', () => {
		it( 'Title normalization should return permanent redirect (301)', async () => {
			const redirectDbKey = utils.dbkey( redirectPage );
			const { status, text, headers } = await client.get( `${ pathPrefix }/page/${ redirectPage }`, { flavor: 'edit' } );
			const { host, search, pathname } = parseURL( headers.location );
			assert.include( search, 'flavor=edit' );
			assert.notInclude( search, 'redirect=no' );
			assert.deepEqual( host, '' );
			assert.include( pathname, `/page/${ redirectDbKey }` );
			assert.deepEqual( status, 301, text );
		} );

		it( 'When a wiki redirect exists, it should be present in the body response', async () => {
			const redirectPageDbkey = utils.dbkey( redirectPage );
			const redirectedPageDbKey = utils.dbkey( redirectedPage );
			const res = await client.get( `${ pathPrefix }/page/${ redirectPageDbkey }` );
			const { status, body: { redirect_target }, text, headers } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.match( redirect_target, new RegExp( `/page/${ encodeURIComponent( redirectedPageDbKey ) }\\?redirect=no$` ) );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Should successfully return page source and metadata for Wikitext page', async () => {
			const res = await client.get( `${ pathPrefix }/page/${ page }` );
			const { status, body, text, headers } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.match( headers.vary, /\bx-restbase-compat\b/ );
			assert.containsAllKeys( body, [ 'latest', 'id', 'key', 'license', 'title', 'content_model', 'source' ] );
			assert.nestedPropertyVal( body, 'content_model', 'wikitext' );
			assert.nestedPropertyVal( body, 'title', pageWithSpaces );
			assert.nestedPropertyVal( body, 'key', utils.dbkey( page ) );
			assert.nestedPropertyVal( body, 'source', baseEditText );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'Should return 404 error for non-existent page', async () => {
			const dummyPageTitle = utils.title( 'DummyPage_' );
			const { status } = await client.get( `${ pathPrefix }/page/${ dummyPageTitle }` );
			assert.deepEqual( status, 404 );
		} );
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
		it( 'Should have appropriate response headers', async () => {
			const preEditResponse = await client.get( `${ pathPrefix }/page/${ page }` );
			const preEditDate = new Date( preEditResponse.body.latest.timestamp );
			const preEditEtag = preEditResponse.headers.etag;

			await mindy.edit( page, { text: "'''Edit 3'''" } );
			const postEditResponse = await client.get( `${ pathPrefix }/page/${ page }` );
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
	} );

	describe( 'GET /page/{title}/bare', () => {
		it( 'Title normalization should return permanent redirect (301)', async () => {
			const { status, text, headers } = await client.get( `${ pathPrefix }/page/${ redirectPage }/bare`, { flavor: 'edit' } );
			const { search } = parseURL( headers.location );
			assert.include( search, 'flavor=edit' );
			assert.notInclude( search, 'redirect=no' );
			assert.deepEqual( status, 301, text );
		} );

		it( 'When a wiki redirect exists, it should be present in the body response', async () => {
			const redirectPageDbkey = utils.dbkey( redirectPage );
			const redirectedPageDbKey = utils.dbkey( redirectedPage );
			const res = await client.get( `${ pathPrefix }/page/${ redirectPageDbkey }/bare` );
			const { status, body: { redirect_target }, text, headers } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.match( redirect_target, new RegExp( `/page/${ encodeURIComponent( redirectedPageDbKey ) }/bare\\?redirect=no$` ) );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Should successfully return page bare', async () => {
			const res = await client.get( `${ pathPrefix }/page/${ page }/bare` );
			const { status, body, text, headers } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.containsAllKeys( body, [ 'latest', 'id', 'key', 'license', 'title', 'content_model', 'html_url' ] );
			assert.nestedPropertyVal( body, 'content_model', 'wikitext' );
			assert.nestedPropertyVal( body, 'title', pageWithSpaces );
			assert.nestedPropertyVal( body, 'key', utils.dbkey( page ) );
			assert.match( body.html_url, new RegExp( `/page/${ encodeURIComponent( pageWithSpaces ) }/html$` ) );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'Should return 404 error for non-existent page, even if a variant exists', async () => {
			const agepayDbkey = utils.dbkey( agepay );
			const { status } = await client.get( `${ pathPrefix }/page/${ agepayDbkey }/bare` );
			assert.deepEqual( status, 404 );
		} );
		it( 'Should have appropriate response headers', async () => {
			const preEditResponse = await client.get( `${ pathPrefix }/page/${ page }/bare` );
			const preEditDate = new Date( preEditResponse.body.latest.timestamp );
			const preEditEtag = preEditResponse.headers.etag;

			await mindy.edit( page, { text: "'''Edit 4'''" } );
			const postEditResponse = await client.get( `${ pathPrefix }/page/${ page }/bare` );
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
	} );

	describe( 'GET /page/{title}/bare with x-restbase-compat', () => {
		it( 'Should successfully return restbase-compatible revision meta-data', async () => {
			const { status, body, text, headers } = await client
				.get( `${ pathPrefix }/page/${ page }/bare` )
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

		it( 'Should successfully return restbase-compatible errors', async () => {
			const dummyPageTitle = utils.title( 'DummyPage_' );
			const { status, body, text, headers } = await client
				.get( `${ pathPrefix }/page/${ dummyPageTitle }/bare` )
				.set( 'x-restbase-compat', 'true' );

			assert.deepEqual( status, 404, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.containsAllKeys( body, [ 'type', 'title', 'method', 'detail', 'uri' ] );
		} );
	} );

	describe( 'GET /page/{title}/html', () => {
		it( 'Title normalization should return permanent redirect (301)', async () => {
			const { status, text, headers } = await client.get( `${ pathPrefix }/page/${ redirectPage }/html`, { flavor: 'edit' } );
			const { search } = parseURL( headers.location );
			assert.include( search, 'flavor=edit' );
			assert.deepEqual( status, 301, text );
		} );

		it( 'Wiki redirects should return temporary redirect (307)', async () => {
			const redirectPageDbkey = utils.dbkey( redirectPage );
			const redirectedPageDbkey = utils.dbkey( redirectedPage );
			const { status, text, headers } = await client.get( `${ pathPrefix }/page/${ redirectPageDbkey }/html`, { flavor: 'edit' } );
			const { host, pathname, search } = parseURL( headers.location );
			assert.include( search, 'flavor=edit' );
			assert.include( search, 'redirect=no' );
			assert.include( pathname, `/page/${ redirectedPageDbkey }` );
			assert.deepEqual( host, '' );
			assert.deepEqual( status, 307, text );
		} );

		it( 'Variant redirects should return temporary redirect (307)', async () => {
			const agepayDbkey = utils.dbkey( agepay );
			const atinlayAgepayDbkey = utils.dbkey( atinlayAgepay );
			const { status, text, headers } = await client.get( `${ pathPrefix }/page/${ agepayDbkey }/html` );
			const { host, pathname, search } = parseURL( headers.location );
			assert.include( search, 'redirect=no' );
			assert.deepEqual( host, '' );
			assert.include( pathname, `/page/${ atinlayAgepayDbkey }` );
			assert.deepEqual( status, 307, text );
		} );

		it( 'Bypass wiki redirects with query param redirect=no', async () => {
			const redirectPageDbkey = utils.dbkey( redirectPage );
			const res = await client.get(
				`${ pathPrefix }/page/${ redirectPageDbkey }/html`,
				{ redirect: 'no' }
			);
			const { status, text, headers } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^text\/html/ );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Bypass wiki redirects with query param redirect=false', async () => {
			const redirectPageDbkey = utils.dbkey( redirectPage );
			const res = await client.get(
				`${ pathPrefix }/page/${ redirectPageDbkey }/html`,
				{ redirect: 'false' }
			);
			const { status, text, headers } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^text\/html/ );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Bypass variant redirects with query param redirect=no', async () => {
			const agepayDbkey = utils.dbkey( agepay );
			const { status, headers } = await client.get(
				`${ pathPrefix }/page/${ agepayDbkey }/html`,
				{ redirect: 'no' }
			);
			assert.deepEqual( status, 404 );
			// rest-nonexistent-title error object returned instead of HTML
			assert.match( headers[ 'content-type' ], /^application\/json/ );
		} );

		it( 'Should successfully return page HTML', async () => {
			const res = await client.get( `${ pathPrefix }/page/${ page }/html` );
			const { status, headers, text } = res;
			assert.deepEqual( status, 200, text );
			assert.nestedProperty( headers, 'date' );
			assert.nestedProperty( headers, 'etag' );
			assert.match( headers[ 'content-type' ], /^text\/html/ );
			assert.match( text, /<html\b/ );
			assert.match( text, /Edit \w+<\/b>/ );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'Should return a 304 on if-modified-since', async () => {
			const res = await client.get( `${ pathPrefix }/page/${ page }/html` );
			const { headers } = res;
			const { 'last-modified': lastModified, etag } = headers;

			const res2 = await client.get( `${ pathPrefix }/page/${ page }/html` )
				.set( 'if-modified-since', lastModified );

			const { status: status2, headers: headers2, text: text2 } = res2;
			assert.deepEqual( status2, 304, text2 );

			// See T357603
			assert.nestedPropertyVal( headers2, 'etag', etag );
			assert.nestedPropertyVal( headers2, 'last-modified', lastModified );
		} );
		it( 'Should successfully return page HTML for a system message', async () => {
			const msg = 'MediaWiki:Newpage-desc';
			const res = await client.get( `${ pathPrefix }/page/${ msg }/html` );
			const { status, headers, text } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^text\/html/ );
			assert.match( text, /<html\b/ );
			assert.match( text, /Start a new page/ );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'Should return 404 error for non-existent page', async () => {
			const dummyPageTitle = utils.title( 'DummyPage_' );
			const { status } = await client.get( `${ pathPrefix }/page/${ dummyPageTitle }/html` );
			assert.deepEqual( status, 404 );
		} );
		it( 'Should have appropriate response headers', async () => {
			const preEditResponse = await client.get( `${ pathPrefix }/page/${ page }/html` );
			const preEditDate = new Date( preEditResponse.headers[ 'last-modified' ] );
			const preEditEtag = preEditResponse.headers.etag;

			await mindy.edit( page, { text: "'''Edit XYZ'''" } );
			const postEditResponse = await client.get( `${ pathPrefix }/page/${ page }/html` );
			const postEditDate = new Date( postEditResponse.headers[ 'last-modified' ] );
			const postEditHeaders = postEditResponse.headers;
			const postEditEtag = postEditResponse.headers.etag;

			assert.containsAllKeys( postEditHeaders, [ 'etag', 'cache-control', 'last-modified' ] );
			assert.match( postEditHeaders[ 'cache-control' ], /\bmax-age=\d/ );
			assert.strictEqual( isNaN( preEditDate.getTime() ), false );
			assert.strictEqual( isNaN( postEditDate.getTime() ), false );
			assert.notEqual( preEditDate, postEditDate );
			assert.notEqual( preEditEtag, postEditEtag );
			assert.match( postEditHeaders.etag, /^".*"$/, 'ETag must be present and not marked weak' );
		} );
		it( 'Should perform variant conversion', async () => {
			await mindy.edit( variantPage, { text: '<p>test language conversion</p>' } );
			const { headers, text } = await client.get( `${ pathPrefix }/page/${ variantPage }/html`, null, {
				'accept-language': 'en-x-piglatin'
			} );

			assert.match( text, /esttay anguagelay onversioncay/ );
			assert.match( headers[ 'content-type' ], /^text\/html/ );
			assert.match( headers.vary, /\bAccept-Language\b/i );
			assert.match( headers[ 'content-language' ], /en-x-piglatin/i );
			assert.match( headers.etag, /en-x-piglatin/i );
		} );
		it( 'Should perform fallback variant conversion', async () => {
			await mindy.edit( fallbackVariantPage, { text: 'Podvlačenje linkova:' } );
			const { headers, text } = await client.get( `${ pathPrefix }/page/${ encodeURIComponent( fallbackVariantPage ) }/html`, null, {
				'accept-language': 'sh-cyrl'
			} );

			assert.match( text, /Подвлачење линкова:/ );
			assert.match( headers[ 'content-type' ], /^text\/html/ );
			assert.match( headers.vary, /\bAccept-Language\b/i );
			assert.match( headers[ 'content-language' ], /sh-cyrl/i );
			assert.match( headers.etag, /sh-cyrl/i );
		} );
	} );

	describe( 'GET /page/{title}/html with x-restbase-compat', () => {
		it( 'Should successfully return restbase-compatible errors', async () => {
			const dummyPageTitle = utils.title( 'DummyPage_' );
			const { status, body, text, headers } = await client
				.get( `${ pathPrefix }/page/${ dummyPageTitle }/html` )
				.set( 'x-restbase-compat', 'true' );

			assert.deepEqual( status, 404, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.containsAllKeys( body, [ 'type', 'title', 'method', 'detail', 'uri' ] );
		} );
	} );

	describe( 'GET /page/{title}/with_html', () => {
		it( 'Title normalization should return permanent redirect (301)', async () => {
			const { status, text, headers } = await client.get( `${ pathPrefix }/page/${ redirectPage }/with_html`, { flavor: 'edit' } );
			const { search } = parseURL( headers.location );
			assert.include( search, 'flavor=edit' );
			assert.deepEqual( status, 301, text );
		} );

		it( 'Wiki redirects should return temporary redirect (307)', async () => {
			const redirectPageDbkey = utils.dbkey( redirectPage );
			const { status, text, headers } = await client.get( `${ pathPrefix }/page/${ redirectPageDbkey }/with_html`, { flavor: 'edit' } );
			const { search } = parseURL( headers.location );
			assert.include( search, 'flavor=edit' );
			assert.deepEqual( status, 307, text );
		} );

		it( 'Bypass redirects with query param redirect=no', async () => {
			const redirectPageDbkey = utils.dbkey( redirectPage );
			const redirectedPageDbKey = utils.dbkey( redirectedPage );
			const res = await client.get(
				`${ pathPrefix }/page/${ redirectPageDbkey }/with_html`,
				{ redirect: 'no' }
			);
			const { status, body: { redirect_target }, text, headers } = res;
			assert.match( redirect_target, new RegExp( `/page/${ encodeURIComponent( redirectedPageDbKey ) }/with_html` ) );
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );

		it( 'Should successfully return page HTML and metadata for Wikitext page', async () => {
			const res = await client.get( `${ pathPrefix }/page/${ page }/with_html` );
			const { status, body, text, headers } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.containsAllKeys( body, [ 'latest', 'id', 'key', 'license', 'title', 'content_model', 'html' ] );
			assert.nestedPropertyVal( body, 'content_model', 'wikitext' );
			assert.nestedPropertyVal( body, 'title', pageWithSpaces );
			assert.nestedPropertyVal( body, 'key', utils.dbkey( page ) );
			assert.match( body.html, /<html\b/ );
			assert.match( body.html, /Edit \w+<\/b>/ );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'Should successfully return page HTML and metadata for a system message', async () => {
			const msg = 'MediaWiki:Newpage-desc';
			const res = await client.get( `${ pathPrefix }/page/${ msg }/with_html` );
			const { status, body, text, headers } = res;
			assert.deepEqual( status, 200, text );
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.containsAllKeys( body, [ 'latest', 'id', 'key', 'license', 'title', 'content_model', 'html' ] );
			assert.nestedPropertyVal( body, 'content_model', 'wikitext' );
			assert.nestedPropertyVal( body, 'title', msg );
			assert.nestedPropertyVal( body, 'key', utils.dbkey( msg ) );
			assert.nestedPropertyVal( body, 'id', 0 );
			assert.nestedPropertyVal( body.latest, 'id', 0 );
			assert.match( body.html, /<html\b/ );
			assert.match( body.html, /Start a new page/ );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'Should return 404 error for non-existent page', async () => {
			const dummyPageTitle = utils.title( 'DummyPage_' );
			const { status } = await client.get( `${ pathPrefix }/page/${ dummyPageTitle }/with_html` );
			assert.deepEqual( status, 404 );
		} );
		it( 'Should have appropriate response headers', async () => {
			const preEditResponse = await client.get( `${ pathPrefix }/page/${ page }/with_html` );
			const preEditRevDate = new Date( preEditResponse.body.latest.timestamp );
			const preEditEtag = preEditResponse.headers.etag;

			await mindy.edit( page, { text: "'''Edit ABCD'''" } );
			const postEditResponse = await client.get( `${ pathPrefix }/page/${ page }/with_html` );
			const postEditRevDate = new Date( postEditResponse.body.latest.timestamp );
			const postEditHeaders = postEditResponse.headers;
			const postEditEtag = postEditResponse.headers.etag;

			assert.containsAllKeys( postEditHeaders, [ 'etag', 'last-modified' ] );
			const postEditHeaderDate = new Date( postEditHeaders[ 'last-modified' ] );

			// The last-modified date is the render timestamp, which may be newer than the revision
			assert.strictEqual( postEditRevDate.valueOf() <= postEditHeaderDate.valueOf(), true );
			assert.match( postEditHeaders[ 'cache-control' ], /\bmax-age=\d/ );
			assert.strictEqual( isNaN( preEditRevDate.getTime() ), false );
			assert.strictEqual( isNaN( postEditRevDate.getTime() ), false );
			assert.notEqual( preEditRevDate, postEditRevDate );
			assert.notEqual( preEditEtag, postEditEtag );
		} );
		it( 'Should perform variant conversion', async () => {
			await mindy.edit( variantPage, { text: '<p>test language conversion</p>' } );
			const { headers, text } = await client.get( `${ pathPrefix }/page/${ variantPage }/html`, null, {
				'accept-language': 'en-x-piglatin'
			} );

			assert.match( text, /esttay anguagelay onversioncay/ );
			assert.match( headers[ 'content-type' ], /^text\/html/ );
			assert.match( headers.vary, /\bAccept-Language\b/i );
			assert.match( headers.etag, /en-x-piglatin/i );

			// Since with_html returns JSON, content language is not set
			// but if its set, we expect it to be set correctly.
			const contentLanguageHeader = headers[ 'content-language' ];
			if ( contentLanguageHeader ) {
				assert.match( headers[ 'content-language' ], /en-x-piglatin/i );
			}
		} );
		it( 'Should perform fallback variant conversion', async () => {
			await mindy.edit( fallbackVariantPage, { text: 'Podvlačenje linkova:' } );
			const { headers, text } = await client.get( `${ pathPrefix }/page/${ encodeURIComponent( fallbackVariantPage ) }/html`, null, {
				'accept-language': 'sh-cyrl'
			} );

			assert.match( text, /Подвлачење линкова:/ );
			assert.match( headers[ 'content-type' ], /^text\/html/ );
			assert.match( headers.vary, /\bAccept-Language\b/i );
			assert.match( headers.etag, /sh-cyrl/i );

			// Since with_html returns JSON, content language is not set
			// but if its set, we expect it to be set correctly.
			const contentLanguageHeader = headers[ 'content-language' ];
			if ( contentLanguageHeader ) {
				assert.match( headers[ 'content-language' ], /sh-cyrl/i );
			}
		} );
	} );
} );

// eslint-disable-next-line mocha/no-exports
exports.init = function ( pp, sm ) {
	// Allow testing both legacy and module paths using the same tests
	pathPrefix = pp;
	specModule = sm;
};
