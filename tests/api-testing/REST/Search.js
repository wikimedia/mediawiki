'use strict';

const { action, assert, REST, utils, wiki } = require( 'api-testing' );
const superagent = require( 'superagent' );
const xml2js = require( 'xml2js' );

const chai = require( 'chai' );
const expect = chai.expect;

const chaiResponseValidator = require( 'chai-openapi-response-validator' ).default;

describe( 'Search', () => {
	const client = new REST( 'rest.php' );
	const sharedTitleTerm = 'X' + utils.uniq(); // NOTE: must start with upper-case letter
	const pageWithBothTerms = utils.title( `${ sharedTitleTerm }XXX` );
	const pageWithOneTerm = utils.title( `${ sharedTitleTerm }YYY` );
	const pageWithOwnTitle = utils.title( 'ZZZ' );
	const searchTerm = utils.uniq();
	const searchTerm2 = utils.uniq();
	let alice;
	let mindy;
	let openApiSpec;

	before( async () => {
		alice = await action.alice();
		mindy = await action.mindy();
		await alice.edit( pageWithBothTerms, { text: `${ searchTerm } ${ searchTerm2 }` } );
		await alice.edit( pageWithOneTerm, { text: searchTerm2 } );
		await alice.edit( pageWithOwnTitle, { text: pageWithOwnTitle } );
		await wiki.runAllJobs();

		const { status, text } = await client.get( '/specs/v0/module/-' );
		assert.deepEqual( status, 200 );

		openApiSpec = JSON.parse( text );
		chai.use( chaiResponseValidator( openApiSpec ) );
	} );

	describe( 'GET /search/page?q={term}', () => {
		it( 'should return empty array when search term has no title or text matches', async () => {
			const nonExistentTerm = utils.uniq();
			const res = await client.get( `/v1/search/page?q=${ nonExistentTerm }` );
			const { body, headers } = res;
			const noResultsResponse = { pages: [] };
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.deepEqual( noResultsResponse, body );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'should return array of pages when there is only a text match', async () => {
			const res = await client.get( `/v1/search/page?q=${ searchTerm }` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 1 );
			const returnPage = body.pages[ 0 ];
			assert.nestedProperty( returnPage, 'title' );
			assert.nestedProperty( returnPage, 'id' );
			assert.nestedProperty( returnPage, 'key' );
			assert.nestedProperty( returnPage, 'excerpt' );
			assert.nestedPropertyVal( returnPage, 'thumbnail', null );
			assert.nestedPropertyVal( returnPage, 'description', null );
			assert.nestedPropertyVal( returnPage, 'matched_title', null );
			assert.nestedPropertyVal( returnPage, 'anchor', null );
			assert.include( returnPage.excerpt, `<span class="searchmatch">${ searchTerm }</span>` );

			// full-text search should not have cache-control
			if ( headers[ 'cache-control' ] ) {
				assert.notMatch( headers[ 'cache-control' ], /\bpublic\b/ );
				assert.notMatch( headers[ 'cache-control' ], /\bmax-age=0*[1-9]\b/ );
				assert.notMatch( headers[ 'cache-control' ], /\bs-maxage=0*[1-9]\b/ );
			}
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;

		} );
		it( 'should return array of pages when there is only title match', async () => {
			const res = await client.get( `/v1/search/page?q=${ pageWithBothTerms }` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 1 );
			const returnPage = body.pages[ 0 ];
			assert.nestedProperty( returnPage, 'title' );
			assert.nestedProperty( returnPage, 'id' );
			assert.nestedProperty( returnPage, 'key' );
			assert.nestedPropertyVal( returnPage, 'excerpt', null );
			assert.nestedPropertyVal( returnPage, 'thumbnail', null );
			assert.nestedPropertyVal( returnPage, 'description', null );
			assert.nestedPropertyVal( returnPage, 'matched_title', null );
			assert.nestedPropertyVal( returnPage, 'anchor', null );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'should return a single page when there is a title and text match on the same page', async () => {
			const res = await client.get( `/v1/search/page?q=${ pageWithOwnTitle }` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 1 );
			const returnPage = body.pages[ 0 ];
			assert.nestedProperty( returnPage, 'title' );
			assert.nestedProperty( returnPage, 'id' );
			assert.nestedProperty( returnPage, 'key' );
			assert.nestedPropertyVal( returnPage, 'title', pageWithOwnTitle );
			assert.nestedPropertyVal( returnPage, 'thumbnail', null );
			assert.nestedPropertyVal( returnPage, 'description', null );
			assert.nestedPropertyVal( returnPage, 'matched_title', null );
			assert.nestedPropertyVal( returnPage, 'anchor', null );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'should return two pages when both pages match', async () => {
			const res = await client.get( `/v1/search/page?q=${ searchTerm2 }` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 2 );
			const returnedPages = [ body.pages[ 0 ].title, body.pages[ 1 ].title ];
			const expectedPages = [ pageWithBothTerms, pageWithOneTerm ];
			assert.equal( expectedPages.sort().join( '|' ), returnedPages.sort().join( '|' ) );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'should return only one page when two pages match but limit is 1', async () => {
			const res = await client.get( `/v1/search/page?q=${ searchTerm2 }&limit=1` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 1 );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'should not return results when page with term has been deleted', async () => {
			const pageToDelete = 'Delete Page';
			const deleteTerm = `Delete_${ utils.uniq() }`;
			const { title } = await alice.edit( pageToDelete, { text: deleteTerm } );
			await mindy.action( 'delete', {
				title,
				summary: 'testing',
				token: await mindy.token( 'csrf' )
			}, 'POST' );
			await wiki.runAllJobs();
			const res = await client.get( `/v1/search/page?q=${ deleteTerm }` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 0 );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'should ignore duplicate redirect source and target if both pages are a match', async () => {
			const redirectSource = utils.title( 'redirect_source_' );
			const redirectTarget = utils.title( 'redirect_target_' );
			const uniquePageText = utils.uniq();

			const { title: redirectTargetTitle } = await alice.edit( redirectTarget, { text: `${ uniquePageText }` } );

			await alice.edit( redirectSource,
				{ text: `#REDIRECT [[ ${ redirectTarget } ]]. ${ uniquePageText }.` }
			);

			await wiki.runAllJobs();
			const res = await client.get( `/v1/search/page?q=${ uniquePageText }` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 1 );
			assert.nestedPropertyVal( body.pages[ 0 ], 'title', redirectTargetTitle );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
	} );

	describe( 'GET /search/title?q={term}', () => {
		it( 'should return empty array when search term has no title matches', async () => {
			const nonExistentTerm = utils.uniq();
			const res = await client.get( `/v1/search/title?q=${ nonExistentTerm }` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 0 );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'should not return pages when there is only a text match', async () => {
			const res = await client.get( `/v1/search/title?q=${ searchTerm }` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 0 );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'should return array of pages when there is a title match', async () => {
			const res = await client.get( `/v1/search/title?q=${ pageWithBothTerms }` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 1 );
			const returnPage = body.pages[ 0 ];
			assert.nestedProperty( returnPage, 'title' );
			assert.nestedProperty( returnPage, 'id' );
			assert.nestedProperty( returnPage, 'key' );
			assert.nestedProperty( returnPage, 'excerpt' );
			assert.nestedPropertyVal( returnPage, 'thumbnail', null );
			assert.nestedPropertyVal( returnPage, 'description', null );
			assert.nestedPropertyVal( returnPage, 'matched_title', null );
			assert.nestedPropertyVal( returnPage, 'anchor', null );

			// completion search should encourage caching
			assert.nestedProperty( headers, 'cache-control' );
			assert.match( headers[ 'cache-control' ], /\bpublic\b/ );
			assert.match( headers[ 'cache-control' ], /\bmax-age=[1-9]\d*/ );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'should return two pages when both pages match', async () => {
			const res = await client.get( `/v1/search/title?q=${ sharedTitleTerm }` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 2 );
			const returnedPages = [ body.pages[ 0 ].title, body.pages[ 1 ].title ];
			const expectedPages = [ pageWithBothTerms, pageWithOneTerm ];
			assert.equal( expectedPages.sort().join( '|' ), returnedPages.sort().join( '|' ) );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'should return only one page when two pages match but limit is 1', async () => {
			const res = await client.get( `/v1/search/title?q=${ sharedTitleTerm }&limit=1` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 1 );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'should not return deleted page', async () => {
			const deleteTerm = utils.uniq();
			const pageToDelete = utils.title( `${ deleteTerm }_test_` );
			const { title } = await alice.edit( pageToDelete, { text: deleteTerm } );
			await mindy.action( 'delete', {
				title,
				summary: 'testing',
				token: await mindy.token( 'csrf' )
			}, 'POST' );
			await wiki.runAllJobs();
			const res = await client.get( `/v1/search/title?q=${ deleteTerm }` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 0 );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'should include redirect for page if one exists', async () => {
			const redirectSource = utils.title( 'redirect_source_' );
			const redirectTarget = utils.title( 'redirect_target_' );

			const { title: redirectTargetTitle } = await alice.edit( redirectTarget, { text: 'foo' } );

			const { title: redirectSourceTitle } = await alice.edit( redirectSource,
				{ text: `#REDIRECT [[ ${ redirectTarget } ]]` }
			);

			await wiki.runAllJobs();

			const res = await client.get( `/v1/search/title?q=${ redirectSourceTitle }` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 1 );
			assert.nestedPropertyVal( body.pages[ 0 ], 'title', redirectTargetTitle );
			assert.nestedPropertyVal( body.pages[ 0 ], 'matched_title', redirectSourceTitle );
			assert.nestedPropertyVal( body.pages[ 0 ], 'anchor', null );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
		it( 'should include redirect for page if one exists, with an anchor', async () => {
			const redirectSource = utils.title( 'redirect_source_' );
			const redirectTarget = utils.title( 'redirect_target_' );
			const anchor = 'Test anchor';

			const { title: redirectTargetTitle } = await alice.edit( redirectTarget, { text: 'foo' } );

			const { title: redirectSourceTitle } = await alice.edit( redirectSource,
				{ text: `#REDIRECT [[ ${ redirectTarget }#${ anchor } ]]` }
			);

			await wiki.runAllJobs();

			const res = await client.get( `/v1/search/title?q=${ redirectSourceTitle }` );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/json/ );
			assert.lengthOf( body.pages, 1 );
			assert.nestedPropertyVal( body.pages[ 0 ], 'title', redirectTargetTitle );
			assert.nestedPropertyVal( body.pages[ 0 ], 'matched_title', redirectSourceTitle );
			assert.nestedPropertyVal( body.pages[ 0 ], 'anchor', anchor );
			// eslint-disable-next-line no-unused-expressions
			expect( res ).to.satisfyApiSpec;
		} );
	} );

	describe( 'GET /search', () => {
		// enable XML parsing
		function parseXML( res, cb ) {
			res.text = '';
			res.on( 'data', ( chunk ) => {
				res.text += chunk;
			} );
			res.on( 'end', () => xml2js.parseString( res.text, cb ) );
		}

		superagent.parse[ 'application/xml' ] = parseXML;
		superagent.parse[ 'application/opensearchdescription+xml' ] = parseXML;

		it( 'should return an OpenSearch Description document', async () => {
			const res = await client.get( '/v1/search' );
			const { body, headers } = res;
			assert.match( headers[ 'content-type' ], /^application\/opensearchdescription\+xml/ );
			assert.nestedProperty( body, 'OpenSearchDescription' );
			// TODO: assert more about the structure
		} );

		it( 'should support plain XML output', async () => {
			const res = await client.get( '/v1/search' )
				.query( { ctype: 'application/xml' } );
			const { body, headers } = res;

			assert.match( headers[ 'content-type' ], /^application\/xml/ );
			assert.nestedProperty( body, 'OpenSearchDescription' );
		} );
	} );
} );
