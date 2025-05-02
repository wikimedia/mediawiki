/* global fetchMock, process */
const restSearchClient = require( '../../../resources/src/mediawiki.skinning.typeaheadSearch/restSearchClient.js' );
const jestFetchMock = require( 'jest-fetch-mock' );
const urlGeneratorFn = require( '../../../resources/src/mediawiki.skinning.typeaheadSearch/urlGenerator.js' );
const scriptPath = '/w/index.php';
const urlGenerator = urlGeneratorFn( scriptPath );
const searchApiUrl = 'https://en.wikipedia.org/w/rest.php';
const mockedRequests = !process.env.TEST_LIVE_REQUESTS;

describe( 'restApiSearchClient', () => {
	beforeAll( () => {
		jestFetchMock.enableFetchMocks();
	} );

	afterAll( () => {
		jestFetchMock.disableFetchMocks();
	} );

	beforeEach( () => {
		fetchMock.resetMocks();
		if ( !mockedRequests ) {
			fetchMock.disableMocks();
		}
	} );

	test( '2 results', async () => {
		const thumbUrl = '//upload.wikimedia.org/wikipedia/commons/0/01/MediaWiki-smaller-logo.png';
		const restResponse = {
			pages: [
				{
					id: 37298,
					key: 'Media',
					label: 'Media',
					title: 'Media',
					description: 'Wikimedia disambiguation page',
					thumbnail: null,
					url: '/w/index.php?title=Special%3ASearch&search=Media',
					value: 37298
				},
				{
					id: 323710,
					key: 'MediaWiki',
					label: 'MediaWiki',
					title: 'MediaWiki',
					description: 'wiki software',
					thumbnail: {
						width: 200,
						height: 189,
						url: thumbUrl
					},
					url: '/w/index.php?title=Special%3ASearch&search=MediaWiki',
					value: 323710
				}
			]
		};
		fetchMock.mockOnce( JSON.stringify( restResponse ) );

		const searchResult = await restSearchClient( searchApiUrl, urlGenerator ).fetchByTitle(
			'media',
			2
		).fetch;

		const controller = new AbortController();

		expect( searchResult.query ).toStrictEqual( 'media' );
		expect( searchResult.results ).toBeTruthy();
		expect( searchResult.results.length ).toBe( 2 );

		expect( searchResult.results[ 0 ] ).toStrictEqual(
			Object.assign( {}, restResponse.pages[ 0 ], {
				// thumbnail: null -> thumbnail: undefined
				thumbnail: undefined
			} ) );
		expect( searchResult.results[ 1 ] ).toStrictEqual( restResponse.pages[ 1 ] );

		if ( mockedRequests ) {
			expect( fetchMock ).toHaveBeenCalledTimes( 1 );
			expect( fetchMock ).toHaveBeenCalledWith(
				'https://en.wikipedia.org/w/rest.php/v1/search/title?q=media&limit=2',
				{ headers: { accept: 'application/json' }, signal: controller.signal }
			);
		}
	} );

	test( '0 results', async () => {
		const restResponse = { pages: [] };
		fetchMock.mockOnce( JSON.stringify( restResponse ) );

		const searchResult = await restSearchClient( searchApiUrl, urlGenerator ).fetchByTitle(
			'thereIsNothingLikeThis'
		).fetch;

		const controller = new AbortController();
		expect( searchResult.query ).toStrictEqual( 'thereIsNothingLikeThis' );
		expect( searchResult.results ).toBeTruthy();
		expect( searchResult.results.length ).toBe( 0 );

		if ( mockedRequests ) {
			expect( fetchMock ).toHaveBeenCalledTimes( 1 );
			expect( fetchMock ).toHaveBeenCalledWith(
				'https://en.wikipedia.org/w/rest.php/v1/search/title?q=thereIsNothingLikeThis&limit=10',
				{ headers: { accept: 'application/json' }, signal: controller.signal }
			);
		}
	} );

	if ( mockedRequests ) {
		test( 'network error', async () => {
			fetchMock.mockRejectOnce( new Error( 'failed' ) );

			await expect( restSearchClient( searchApiUrl, urlGenerator ).fetchByTitle(
				'anything'
			).fetch ).rejects.toThrow( 'failed' );
		} );
	}
} );
