const instrumentation = require( '../../../resources/src/mediawiki.skinning.typeaheadSearch/instrumentation.js' );

describe( 'instrumentation', () => {
	test.each( [
		[ 0, 'acrw1_0' ],
		[ 1, 'acrw1_1' ],
		[ -1, 'acrw1_-1' ]
	] )( 'getWprovFromResultIndex( %d ) = %s', ( index, expected ) => {
		expect( instrumentation.getWprovFromResultIndex( index ) )
			.toBe( expected );
	} );

	test( 'addWprovToSearchResultUrls without offset', () => {
		const url1 = 'https://host/?title=Special%3ASearch&search=Aa',
			url2Base = 'https://host/?title=Special%3ASearch&search=Ab',
			url3 = 'https://host/Ac',
			url5 = '/index.php?title=Special%3ASearch&search=Ad';
		const results = [
			{
				title: 'Aa',
				url: url1
			},
			{
				title: 'Ab',
				url: `${ url2Base }&wprov=xyz`
			},
			{
				title: 'Ac',
				url: url3
			},
			{
				title: 'Ad'
			},
			{
				title: 'Ae',
				url: url5
			}
		];

		expect( instrumentation.addWprovToSearchResultUrls( results, 0 ) )
			.toStrictEqual( [
				{
					title: 'Aa',
					url: `${ url1 }&wprov=acrw1_0`
				},
				{
					title: 'Ab',
					url: `${ url2Base }&wprov=acrw1_1`
				},
				{
					title: 'Ac',
					url: `${ url3 }?wprov=acrw1_2`
				},
				{
					title: 'Ad'
				},
				{
					title: 'Ae',
					url: `${ location.origin }${ url5 }&wprov=acrw1_4`
				}
			] );
		expect( results[ 0 ].url ).toStrictEqual( url1 );
	} );

	test( 'addWprovToSearchResultUrls with offset', () => {
		const url1 = 'https://host/?title=Special%3ASearch&search=Ae',
			url2 = 'https://host/?title=Special%3ASearch&search=Af';
		const results = [
			{
				title: 'Ae',
				url: url1
			},
			{
				title: 'Af',
				url: url2
			}
		];

		expect( instrumentation.addWprovToSearchResultUrls( results, 4 ) )
			.toStrictEqual( [
				{
					title: 'Ae',
					url: `${ url1 }&wprov=acrw1_4`
				},
				{
					title: 'Af',
					url: `${ url2 }&wprov=acrw1_5`
				}
			] );
	} );
} );
