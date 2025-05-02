/* global fetchMock, process */
const fetchJson = require( '../../../resources/src/mediawiki.skinning.typeaheadSearch/fetch.js' );
const jestFetchMock = require( 'jest-fetch-mock' );

const mockedRequests = !process.env.TEST_LIVE_REQUESTS;
const url = '//en.wikipedia.org/w/rest.php/v1/search/title?q=jfgkdajgioj&limit=10';

describe( 'abort() using AbortController', () => {
	test( 'Aborting an unfinished request throws an AbortError', async () => {
		expect.assertions( 1 );

		const { abort, fetch } = fetchJson( url );

		abort();

		return fetch.catch( ( e ) => {
			expect( e.name ).toStrictEqual( 'AbortError' );
		} );
	} );
} );

describe( 'fetch() using window.fetch', () => {
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
		fetchMock.mockIf( /^\/\/en.wikipedia.org\//, async ( req ) => {
			if ( req.url === url ) {
				return {
					body: JSON.stringify( { pages: [] } ),
					headers: {
						'Content-Type': 'application/json'
					}
				};
			} else {
				return {
					status: 404,
					body: 'Page not found'
				};
			}
		} );
	} );

	test( '200 without init param passed', async () => {
		const { fetch } = fetchJson( url );
		const json = await fetch;
		const controller = new AbortController();
		expect( json ).toStrictEqual( { pages: [] } );

		if ( mockedRequests ) {
			expect( fetchMock ).toHaveBeenCalledTimes( 1 );
			expect( fetchMock ).toHaveBeenCalledWith( url, { signal: controller.signal } );
		}
	} );

	test( '200 with init param passed', async () => {
		const { fetch } = fetchJson( url, { mode: 'cors' } );
		const json = await fetch;

		expect( json ).toStrictEqual( { pages: [] } );

		if ( mockedRequests ) {
			expect( fetchMock ).toHaveBeenCalledTimes( 1 );
			expect( fetchMock ).toHaveBeenCalledWith(
				url,
				expect.objectContaining( { mode: 'cors' } )
			);
		}
	} );

	test( '404 response', async () => {
		expect.assertions( 1 );
		const { fetch } = fetchJson( '//en.wikipedia.org/doesNotExist' );

		await expect( fetch )
			.rejects.toStrictEqual( 'Network request failed with HTTP code 404' );

		if ( mockedRequests ) {
			const controller = new AbortController();
			expect.assertions( 3 );
			expect( fetchMock ).toHaveBeenCalledTimes( 1 );
			expect( fetchMock ).toHaveBeenCalledWith(
				'//en.wikipedia.org/doesNotExist', { signal: controller.signal }
			);
		}
	} );

} );
