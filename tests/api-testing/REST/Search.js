'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );

describe( 'Search', () => {
	const client = new REST( 'rest.php/v1' );
	const sharedTitleTerm = 'X' + utils.uniq(); // NOTE: must start with upper-case letter
	const pageWithBothTerms = utils.title( `${sharedTitleTerm}XXX` );
	const pageWithOneTerm = utils.title( `${sharedTitleTerm}YYY` );
	const pageWithOwnTitle = utils.title( 'ZZZ' );
	const searchTerm = utils.uniq();
	const searchTerm2 = utils.uniq();
	let alice;
	let mindy;

	before( async () => {
		alice = await action.alice();
		mindy = await action.mindy();
		await alice.edit( pageWithBothTerms, { text: `${searchTerm} ${searchTerm2}` } );
		await alice.edit( pageWithOneTerm, { text: searchTerm2 } );
		await alice.edit( pageWithOwnTitle, { text: pageWithOwnTitle } );
	} );

	describe( 'GET /search/page?q={term}', () => {
		it( 'should return empty array when search term has no title or text matches', async () => {
			const nonExistentTerm = utils.uniq();
			const { body } = await client.get( `/search/page?q=${nonExistentTerm}` );
			const noResultsResponse = { pages: [] };
			assert.deepEqual( noResultsResponse, body );
		} );
		it( 'should return array of pages when there is only a text match', async () => {
			const { body, headers } = await client.get( `/search/page?q=${searchTerm}` );
			assert.lengthOf( body.pages, 1 );
			const returnPage = body.pages[ 0 ];
			assert.nestedProperty( returnPage, 'title' );
			assert.nestedProperty( returnPage, 'id' );
			assert.nestedProperty( returnPage, 'key' );
			assert.nestedProperty( returnPage, 'excerpt' );
			assert.nestedPropertyVal( returnPage, 'thumbnail', null );
			assert.nestedPropertyVal( returnPage, 'description', null );
			assert.include( returnPage.excerpt, `<span class='searchmatch'>${searchTerm}</span>` );

			// full-text search should not have cache-control
			if ( headers[ 'cache-control' ] ) {
				assert.notMatch( headers[ 'cache-control' ], /\bpublic\b/ );
				assert.notMatch( headers[ 'cache-control' ], /\bmax-age=0*[1-9]\b/ );
				assert.notMatch( headers[ 'cache-control' ], /\bs-maxage=0*[1-9]\b/ );
			}
		} );
		it( 'should return array of pages when there is only title match', async () => {
			const { body } = await client.get( `/search/page?q=${pageWithBothTerms}` );
			assert.lengthOf( body.pages, 1 );
			const returnPage = body.pages[ 0 ];
			assert.nestedProperty( returnPage, 'title' );
			assert.nestedProperty( returnPage, 'id' );
			assert.nestedProperty( returnPage, 'key' );
			assert.nestedPropertyVal( returnPage, 'excerpt', null );
			assert.nestedPropertyVal( returnPage, 'thumbnail', null );
			assert.nestedPropertyVal( returnPage, 'description', null );
		} );
		it( 'should return a single page when there is a title and text match on the same page', async () => {
			const { body } = await client.get( `/search/page?q=${pageWithOwnTitle}` );
			assert.lengthOf( body.pages, 1 );
			const returnPage = body.pages[ 0 ];
			assert.nestedProperty( returnPage, 'title' );
			assert.nestedProperty( returnPage, 'id' );
			assert.nestedProperty( returnPage, 'key' );
			assert.nestedPropertyVal( returnPage, 'title', pageWithOwnTitle );
			assert.nestedPropertyVal( returnPage, 'thumbnail', null );
			assert.nestedPropertyVal( returnPage, 'description', null );
		} );
		it( 'should return two pages when both pages match', async () => {
			const { body } = await client.get( `/search/page?q=${searchTerm2}` );
			assert.lengthOf( body.pages, 2 );
			const returnedPages = [ body.pages[ 0 ].title, body.pages[ 1 ].title ];
			const expectedPages = [ pageWithBothTerms, pageWithOneTerm ];
			assert.equal( expectedPages.sort().join( '|' ), returnedPages.sort().join( '|' ) );
		} );
		it( 'should return only one page when two pages match but limit is 1', async () => {
			const { body } = await client.get( `/search/page?q=${searchTerm2}&limit=1` );
			assert.lengthOf( body.pages, 1 );
		} );
		it( 'should not return results when page with term has been deleted', async () => {
			const pageToDelete = 'Delete Page';
			const deleteTerm = `Delete_${utils.uniq()}`;
			const { title } = await alice.edit( pageToDelete, { text: deleteTerm } );
			await mindy.action( 'delete', {
				title,
				summary: 'testing',
				token: await mindy.token( 'csrf' )
			}, 'POST' );
			const { body } = await client.get( `/search/page?q=${deleteTerm}` );
			assert.lengthOf( body.pages, 0 );
		} );
	} );

	describe( 'GET /search/title?q={term}', () => {
		it( 'should return empty array when search term has no title matches', async () => {
			const nonExistentTerm = utils.uniq();
			const { body } = await client.get( `/search/title?q=${nonExistentTerm}` );
			assert.lengthOf( body.pages, 0 );
		} );
		it( 'should not return pages when there is only a text match', async () => {
			const { body } = await client.get( `/search/title?q=${searchTerm}` );
			assert.lengthOf( body.pages, 0 );
		} );
		it( 'should return array of pages when there is a title match', async () => {
			const { body, headers } = await client.get( `/search/title?q=${pageWithBothTerms}` );
			assert.lengthOf( body.pages, 1 );
			const returnPage = body.pages[ 0 ];
			assert.nestedProperty( returnPage, 'title' );
			assert.nestedProperty( returnPage, 'id' );
			assert.nestedProperty( returnPage, 'key' );
			assert.nestedProperty( returnPage, 'excerpt' );
			assert.nestedPropertyVal( returnPage, 'thumbnail', null );
			assert.nestedPropertyVal( returnPage, 'description', null );

			// completion search should encourage caching
			assert.nestedProperty( headers, 'cache-control' );
			assert.match( headers[ 'cache-control' ], /\bpublic\b/ );
			assert.match( headers[ 'cache-control' ], /\bmax-age=[1-9]\d*/ );
		} );
		it( 'should return two pages when both pages match', async () => {
			const { body } = await client.get( `/search/title?q=${sharedTitleTerm}` );
			assert.lengthOf( body.pages, 2 );
			const returnedPages = [ body.pages[ 0 ].title, body.pages[ 1 ].title ];
			const expectedPages = [ pageWithBothTerms, pageWithOneTerm ];
			assert.equal( expectedPages.sort().join( '|' ), returnedPages.sort().join( '|' ) );
		} );
		it( 'should return only one page when two pages match but limit is 1', async () => {
			const { body } = await client.get( `/search/title?q=${sharedTitleTerm}&limit=1` );
			assert.lengthOf( body.pages, 1 );
		} );
		it( 'should not return deleted page', async () => {
			const deleteTerm = utils.uniq();
			const pageToDelete = utils.title( `${deleteTerm}_test_` );
			const { title } = await alice.edit( pageToDelete, { text: deleteTerm } );
			await mindy.action( 'delete', {
				title,
				summary: 'testing',
				token: await mindy.token( 'csrf' )
			}, 'POST' );
			const { body } = await client.get( `/search/title?q=${deleteTerm}` );
			assert.lengthOf( body.pages, 0 );
		} );
	} );
} );
