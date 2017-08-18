/* eslint-disable camelcase */
( function ( mw ) {
	var filterDefinition = [ {
			name: 'group1',
			type: 'send_unselected_if_any',
			filters: [
				{ name: 'filter1' },
				{ name: 'filter2' },
				{ name: 'filter3' }
			]
		}, {
			name: 'group2',
			type: 'string_options',
			separator: ',',
			filters: [
				{ name: 'filter4' },
				{ name: 'filter5' },
				{ name: 'filter6' }
			]
		}, {
			name: 'group3',
			type: 'boolean',
			isSticky: true,
			filters: [
				{ name: 'group3option1' },
				{ name: 'group3option2' },
				{ name: 'group3option3' }
			]
		} ];

	QUnit.module( 'mediawiki.rcfilters - SavedQueriesModel' );

	QUnit.test( 'Initializing queries', function ( assert ) {
		var filtersModel = new mw.rcfilters.dm.FiltersViewModel(),
			queriesModel = new mw.rcfilters.dm.SavedQueriesModel( filtersModel ),
			exampleQueryStructure = {
				version: '2',
				default: '1234',
				queries: {
					'1234': {
						label: 'Query 1234',
						data: {
							params: {
								filter2: '1'
							},
							highlights: {
								filter5_color: 'c2'
							}
						}
					}
				}
			},
			cases = [
				{
					input: {},
					finalState: { version: '2', queries: {} },
					msg: 'Empty initial query structure results in base saved queries structure.'
				},
				{
					input: $.extend( true, {}, exampleQueryStructure ),
					finalState: $.extend( true, {}, exampleQueryStructure ),
					msg: 'Initialization of given query structure does not corrupt the structure.'
				}
			];

		filtersModel.initializeFilters( filterDefinition );

		cases.forEach( function ( testCase ) {
			queriesModel.initialize( testCase.input );

			assert.deepEqual(
				queriesModel.getState(),
				testCase.finalState,
				testCase.msg
			);
		} );
	} );
}( mediaWiki ) );
