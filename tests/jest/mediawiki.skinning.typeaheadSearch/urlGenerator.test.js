const urlGenerator = require( '../../../resources/src/mediawiki.skinning.typeaheadSearch/urlGenerator.js' );

describe( 'urlGenerator', () => {
	describe( 'default', () => {
		test.each( [
			[ 'string', 'title', '&fulltext=1' ],
			[ 'object', { title: 'title', id: 0, key: '' } ]
		] )( 'suggestion as %s', ( _name, suggestion, extraParams = '' ) => {
			expect( urlGenerator( '/w/index.php' ).generateUrl( suggestion ) )
				.toBe( `/w/index.php?title=Special%3ASearch${ extraParams }&search=title` );
		} );

		test( 'anchor', () => {
			expect( urlGenerator( '/w/index.php' ).generateUrl(
				{ title: 'foo', id: 0, key: '', anchor: 'bar' }
			) ).toBe( '/w/index.php?title=Special%3ASearch&search=foo%23bar' );
		} );

		test( 'custom params, articlePath', () => {
			expect( urlGenerator( '/W/INDEX.PHP' ).generateUrl(
				{ title: 'title', id: 0, key: '' },
				{ TITLE: 'SPECIAL:SEARCH' }
			) ).toBe( '/W/INDEX.PHP?TITLE=SPECIAL%3ASEARCH&search=title' );
		} );
	} );
} );
