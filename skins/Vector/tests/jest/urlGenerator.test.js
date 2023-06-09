const urlGenerator = require( '../../resources/skins.vector.search/urlGenerator.js' );

describe( 'urlGenerator', () => {
	describe( 'default', () => {
		test.each( [
			[ 'string', 'title', '&fulltext=1' ],
			[ 'object', { title: 'title', id: 0, key: '' } ]
		] )( 'suggestion as %s', ( _name, suggestion, extraParams = '' ) => {
			const config = {
				get: jest.fn().mockImplementation( ( key, fallback ) => {
					if ( key === 'wgScript' ) {
						return '/w/index.php';
					}
					return fallback;
				} ),
				set: jest.fn()
			};

			expect( urlGenerator( config ).generateUrl( suggestion ) )
				.toBe( `/w/index.php?title=Special%3ASearch${extraParams}&search=title` );
		} );

		test( 'custom params, articlePath', () => {
			const config = {
				get: jest.fn().mockImplementation( ( _key, fallback ) => {
					return fallback;
				} ),
				set: jest.fn()
			};

			expect( urlGenerator( config ).generateUrl(
				{ title: 'title', id: 0, key: '' },
				{ TITLE: 'SPECIAL:SEARCH' },
				'/W/INDEX.PHP'
			) ).toBe( '/W/INDEX.PHP?TITLE=SPECIAL%3ASEARCH&search=title' );
		} );
	} );

	test( 'custom', () => {
		const customGenerator = {};
		const config = {
			get: jest.fn().mockImplementation( ( key, fallback ) => {
				if ( key === 'wgVectorSearchUrlGenerator' ) {
					return customGenerator;
				}
				return fallback;
			} ),
			set: jest.fn()
		};

		expect( urlGenerator( config ) ).toBe( customGenerator );
	} );
} );
