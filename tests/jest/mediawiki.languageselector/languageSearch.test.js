const languageSearchClient = require( '../../../resources/src/mediawiki.languageselector/languageSearch.js' );

const mockGet = jest.fn();
global.mw = {
	Api: jest.fn( () => ( {
		get: mockGet
	} ) )
};

const searchApiUrl = 'https://en.wikipedia.org/w/api.php';

describe( 'languageSearchClient', () => {
	beforeEach( () => {
		mockGet.mockReset();
		global.mw.Api.mockClear();
	} );

	test( '2 results', async () => {
		const apiResponse = {
			languagesearch: [
				{
					code: 'en',
					name: 'English',
					autonym: 'English'
				},
				{
					code: 'en-gb',
					name: 'British English',
					autonym: 'British English'
				}
			]
		};
		mockGet.mockResolvedValue( apiResponse );

		const client = languageSearchClient( searchApiUrl );
		const searchResult = await client.searchLanguages( 'en' );

		expect( global.mw.Api ).toHaveBeenCalledWith( { ajax: { url: searchApiUrl } } );
		expect( mockGet ).toHaveBeenCalledWith( {
			action: 'languagesearch',
			format: 'json',
			formatversion: '2',
			search: 'en'
		} );

		expect( searchResult ).toEqual( apiResponse );
	} );

	test( '0 results', async () => {
		const apiResponse = { languagesearch: [] };
		mockGet.mockResolvedValue( apiResponse );

		const client = languageSearchClient( searchApiUrl );
		const searchResult = await client.searchLanguages( 'thereIsNothingLikeThis' );

		expect( searchResult ).toEqual( apiResponse );
	} );

	test( 'network error', async () => {
		mockGet.mockRejectedValue( new Error( 'failed' ) );

		const client = languageSearchClient( searchApiUrl );
		await expect( client.searchLanguages( 'anything' ) ).rejects.toThrow( 'failed' );
	} );
} );
