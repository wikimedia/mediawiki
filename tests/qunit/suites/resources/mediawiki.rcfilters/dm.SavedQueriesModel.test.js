/* eslint-disable camelcase */
( function ( mw ) {
	var filterDefinition = [ {
			name: 'group1',
			type: 'send_unselected_if_any',
			filters: [
				// Note: The fact filter2 is default means that in the
				// filter representation, filter1 and filter3 are 'true'
				{ name: 'filter1' },
				{ name: 'filter2', default: true },
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
		} ],
		queriesFilterRepresentation = {
			queries: {
				1234: {
					label: 'Item converted',
					data: {
						filters: {
							// - This value is true, but the original filter-representation
							// of the saved queries ran against defaults. Since filter1 was
							// set as default in the definition, the value would actually
							// not appear in the representation itself.
							// It is considered 'true', though, and should appear in the
							// converted result in its parameter representation.
							// >> group1__filter1: true,
							// - The reverse is true for filter3. Filter3 is set as default
							// but we don't want it in this representation of the saved query.
							// Since the filter representation ran against default values,
							// it will appear as 'false' value in this representation explicitly
							// and the resulting parameter representation should have that
							// as the result as well
							group1__filter3: false,
							group2__filter4: true,
							group3__group3option1: true
						},
						highlights: {
							highlight: true,
							filter1: 'c5',
							group3option1: 'c1'
						},
						invert: true
					}
				}
			}
		},
		queriesParamRepresentation = {
			version: '2',
			queries: {
				1234: {
					label: 'Item converted',
					data: {
						params: {
							// filter1 is 'true' so filter2 and filter3 are both '1'
							// in param representation
							filter2: '1', filter3: '1',
							// Group type string_options
							group2: 'filter4',
							// Note - Group3 is sticky, so it won't show in output
							// Invert/highlight toggles
							invert: '1',
							highlight: '1'
						},
						highlights: {
							filter1_color: 'c5',
							group3option1_color: 'c1'
						}
					}
				}
			}
		};

	QUnit.module( 'mediawiki.rcfilters - SavedQueriesModel' );

	QUnit.test( 'Initializing queries', function ( assert ) {
		var filtersModel = new mw.rcfilters.dm.FiltersViewModel(),
			queriesModel = new mw.rcfilters.dm.SavedQueriesModel( filtersModel ),
			exampleQueryStructure = {
				version: '2',
				default: '1234',
				queries: {
					1234: {
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
				},
				{
					// Converting from old structure
					input: $.extend( true, {}, queriesFilterRepresentation ),
					finalState: $.extend( true, {}, queriesParamRepresentation ),
					msg: 'Conversion from filter representation to parameters retains data.'
				},
				{
					// Converting from old structure with default
					input: $.extend( true, { default: '1234' }, queriesFilterRepresentation ),
					finalState: $.extend( true, { default: '1234' }, queriesParamRepresentation ),
					msg: 'Conversion from filter representation to parameters, with default set up, retains data.'
				},
				{
					// New structure
					input: $.extend( true, {}, queriesParamRepresentation ),
					finalState: $.extend( true, {}, queriesParamRepresentation ),
					msg: 'Parameter representation retains its queries structure'
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

	QUnit.test( 'Manipulating queries', function ( assert ) {
		var id1, id2, item1, matchingItem,
			queriesStructure = {},
			filtersModel = new mw.rcfilters.dm.FiltersViewModel(),
			queriesModel = new mw.rcfilters.dm.SavedQueriesModel( filtersModel );

		filtersModel.initializeFilters( filterDefinition );

		// Start with an empty saved queries model
		queriesModel.initialize( {} );

		// Add items
		id1 = queriesModel.addNewQuery(
			'New query 1',
			{
				params: {
					group2: 'filter5',
					highlight: '1'
				},
				highlights: {
					filter1_color: 'c5',
					group3option1_color: 'c1'
				}
			}
		);
		id2 = queriesModel.addNewQuery(
			'New query 2',
			{
				params: {
					filter1: '1',
					filter2: '1',
					invert: '1'
				},
				highlights: {}
			}
		);
		item1 = queriesModel.getItemByID( id1 );

		assert.equal(
			item1.getID(),
			id1,
			'Item created and its data retained successfully'
		);

		// NOTE: All other methods that the item itself returns are
		// tested in the dm.SavedQueryItemModel.test.js file

		// Build the query structure we expect per item
		queriesStructure[ id1 ] = {
			label: 'New query 1',
			data: {
				params: {
					group2: 'filter5',
					highlight: '1'
				},
				highlights: {
					filter1_color: 'c5',
					group3option1_color: 'c1'
				}
			}
		};
		queriesStructure[ id2 ] = {
			label: 'New query 2',
			data: {
				params: {
					filter1: '1',
					filter2: '1',
					invert: '1'
				},
				highlights: {}
			}
		};

		assert.deepEqual(
			queriesModel.getState(),
			{
				version: '2',
				queries: queriesStructure
			},
			'Full query represents current state of items'
		);

		// Add default
		queriesModel.setDefault( id2 );

		assert.deepEqual(
			queriesModel.getState(),
			{
				version: '2',
				default: id2,
				queries: queriesStructure
			},
			'Setting default is reflected in queries state'
		);

		// Remove default
		queriesModel.setDefault( null );

		assert.deepEqual(
			queriesModel.getState(),
			{
				version: '2',
				queries: queriesStructure
			},
			'Removing default is reflected in queries state'
		);

		// Find matching query
		matchingItem = queriesModel.findMatchingQuery(
			{
				params: {
					group2: 'filter5',
					highlight: '1'
				},
				highlights: {
					filter1_color: 'c5',
					group3option1_color: 'c1'
				}
			}
		);
		assert.deepEqual(
			matchingItem.getID(),
			id1,
			'Finding matching item by identical state'
		);

		// Find matching query with 0-values (base state)
		matchingItem = queriesModel.findMatchingQuery(
			{
				params: {
					group2: 'filter5',
					filter1: '0',
					filter2: '0',
					highlight: '1',
					invert: '0'
				},
				highlights: {
					filter1_color: 'c5',
					group3option1_color: 'c1'
				}
			}
		);
		assert.deepEqual(
			matchingItem.getID(),
			id1,
			'Finding matching item by "dirty" state with 0-base values'
		);
	} );
}( mediaWiki ) );
