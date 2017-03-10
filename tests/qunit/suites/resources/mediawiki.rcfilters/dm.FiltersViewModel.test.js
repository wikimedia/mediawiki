( function ( mw, $ ) {
	QUnit.module( 'mediawiki.rcfilters - FiltersViewModel' );

	QUnit.test( 'Setting up filters', function ( assert ) {
		var definition = {
				group1: {
					title: 'Group 1',
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'group1filter1',
							label: 'Group 1: Filter 1',
							description: 'Description of Filter 1 in Group 1'
						},
						{
							name: 'group1filter2',
							label: 'Group 1: Filter 2',
							description: 'Description of Filter 2 in Group 1'
						}
					]
				},
				group2: {
					title: 'Group 2',
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'group2filter1',
							label: 'Group 2: Filter 1',
							description: 'Description of Filter 1 in Group 2'
						},
						{
							name: 'group2filter2',
							label: 'Group 2: Filter 2',
							description: 'Description of Filter 2 in Group 2'
						}
					]
				},
				group3: {
					title: 'Group 3',
					type: 'string_options',
					filters: [
						{
							name: 'group3filter1',
							label: 'Group 3: Filter 1',
							description: 'Description of Filter 1 in Group 3'
						},
						{
							name: 'group3filter2',
							label: 'Group 3: Filter 2',
							description: 'Description of Filter 2 in Group 3'
						}
					]
				}
			},
			model = new mw.rcfilters.dm.FiltersViewModel();

		model.initializeFilters( definition );

		assert.ok(
			model.getItemByName( 'group1filter1' ) instanceof mw.rcfilters.dm.FilterItem &&
			model.getItemByName( 'group1filter2' ) instanceof mw.rcfilters.dm.FilterItem &&
			model.getItemByName( 'group2filter1' ) instanceof mw.rcfilters.dm.FilterItem &&
			model.getItemByName( 'group2filter2' ) instanceof mw.rcfilters.dm.FilterItem &&
			model.getItemByName( 'group3filter1' ) instanceof mw.rcfilters.dm.FilterItem &&
			model.getItemByName( 'group3filter2' ) instanceof mw.rcfilters.dm.FilterItem,
			'Filters instantiated and stored correctly'
		);

		assert.deepEqual(
			model.getSelectedState(),
			{
				group1filter1: false,
				group1filter2: false,
				group2filter1: false,
				group2filter2: false,
				group3filter1: false,
				group3filter2: false
			},
			'Initial state of filters'
		);

		model.toggleFiltersSelected( {
			group1filter1: true,
			group2filter2: true,
			group3filter1: true
		} );
		assert.deepEqual(
			model.getSelectedState(),
			{
				group1filter1: true,
				group1filter2: false,
				group2filter1: false,
				group2filter2: true,
				group3filter1: true,
				group3filter2: false
			},
			'Updating filter states correctly'
		);
	} );

	QUnit.test( 'Finding matching filters', function ( assert ) {
		var matches,
			definition = {
				group1: {
					title: 'Group 1 title',
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'group1filter1',
							label: 'Group 1: Filter 1',
							description: 'Description of Filter 1 in Group 1'
						},
						{
							name: 'group1filter2',
							label: 'Group 1: Filter 2',
							description: 'Description of Filter 2 in Group 1'
						}
					]
				},
				group2: {
					title: 'Group 2 title',
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'group2filter1',
							label: 'Group 2: Filter 1',
							description: 'Description of Filter 1 in Group 2'
						},
						{
							name: 'group2filter2',
							label: 'xGroup 2: Filter 2',
							description: 'Description of Filter 2 in Group 2'
						}
					]
				}
			},
			testCases = [
				{
					query: 'group',
					expectedMatches: {
						group1: [ 'group1filter1', 'group1filter2' ],
						group2: [ 'group2filter1' ]
					},
					reason: 'Finds filters starting with the query string'
				},
				{
					query: 'filter 2 in group',
					expectedMatches: {
						group1: [ 'group1filter2' ],
						group2: [ 'group2filter2' ]
					},
					reason: 'Finds filters containing the query string in their description'
				},
				{
					query: 'title',
					expectedMatches: {
						group1: [ 'group1filter1', 'group1filter2' ],
						group2: [ 'group2filter1', 'group2filter2' ]
					},
					reason: 'Finds filters containing the query string in their group title'
				}
			],
			model = new mw.rcfilters.dm.FiltersViewModel(),
			extractNames = function ( matches ) {
				var result = {};
				Object.keys( matches ).forEach( function ( groupName ) {
					result[ groupName ] = matches[ groupName ].map( function ( item ) {
						return item.getName();
					} );
				} );
				return result;
			};

		model.initializeFilters( definition );

		testCases.forEach( function ( testCase ) {
			matches = model.findMatches( testCase.query );
			assert.deepEqual(
				extractNames( matches ),
				testCase.expectedMatches,
				testCase.reason
			);
		} );

		matches = model.findMatches( 'foo' );
		assert.ok(
			$.isEmptyObject( matches ),
			'findMatches returns an empty object when no results found'
		);
	} );

	QUnit.test( 'getParametersFromFilters', function ( assert ) {
		var definition = {
				group1: {
					title: 'Group 1',
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hidefilter1',
							label: 'Group 1: Filter 1',
							description: 'Description of Filter 1 in Group 1'
						},
						{
							name: 'hidefilter2',
							label: 'Group 1: Filter 2',
							description: 'Description of Filter 2 in Group 1'
						},
						{
							name: 'hidefilter3',
							label: 'Group 1: Filter 3',
							description: 'Description of Filter 3 in Group 1'
						}
					]
				},
				group2: {
					title: 'Group 2',
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hidefilter4',
							label: 'Group 2: Filter 1',
							description: 'Description of Filter 1 in Group 2'
						},
						{
							name: 'hidefilter5',
							label: 'Group 2: Filter 2',
							description: 'Description of Filter 2 in Group 2'
						},
						{
							name: 'hidefilter6',
							label: 'Group 2: Filter 3',
							description: 'Description of Filter 3 in Group 2'
						}
					]
				},
				group3: {
					title: 'Group 3',
					type: 'string_options',
					separator: ',',
					filters: [
						{
							name: 'filter7',
							label: 'Group 3: Filter 1',
							description: 'Description of Filter 1 in Group 3'
						},
						{
							name: 'filter8',
							label: 'Group 3: Filter 2',
							description: 'Description of Filter 2 in Group 3'
						},
						{
							name: 'filter9',
							label: 'Group 3: Filter 3',
							description: 'Description of Filter 3 in Group 3'
						}
					]
				}
			},
			model = new mw.rcfilters.dm.FiltersViewModel();

		model.initializeFilters( definition );

		// Starting with all filters unselected
		assert.deepEqual(
			model.getParametersFromFilters(),
			{
				hidefilter1: 0,
				hidefilter2: 0,
				hidefilter3: 0,
				hidefilter4: 0,
				hidefilter5: 0,
				hidefilter6: 0,
				group3: 'all'
			},
			'Unselected filters return all parameters falsey or \'all\'.'
		);

		// Select 1 filter
		model.toggleFiltersSelected( {
			hidefilter1: true,
			hidefilter2: false,
			hidefilter3: false,
			hidefilter4: false,
			hidefilter5: false,
			hidefilter6: false
		} );
		// Only one filter in one group
		assert.deepEqual(
			model.getParametersFromFilters(),
			{
				// Group 1 (one selected, the others are true)
				hidefilter1: 0,
				hidefilter2: 1,
				hidefilter3: 1,
				// Group 2 (nothing is selected, all false)
				hidefilter4: 0,
				hidefilter5: 0,
				hidefilter6: 0,
				group3: 'all'
			},
			'One filters in one "send_unselected_if_any" group returns the other parameters truthy.'
		);

		// Select 2 filters
		model.toggleFiltersSelected( {
			hidefilter1: true,
			hidefilter2: true,
			hidefilter3: false,
			hidefilter4: false,
			hidefilter5: false,
			hidefilter6: false
		} );
		// Two selected filters in one group
		assert.deepEqual(
			model.getParametersFromFilters(),
			{
				// Group 1 (two selected, the others are true)
				hidefilter1: 0,
				hidefilter2: 0,
				hidefilter3: 1,
				// Group 2 (nothing is selected, all false)
				hidefilter4: 0,
				hidefilter5: 0,
				hidefilter6: 0,
				group3: 'all'
			},
			'One filters in one "send_unselected_if_any" group returns the other parameters truthy.'
		);

		// Select 3 filters
		model.toggleFiltersSelected( {
			hidefilter1: true,
			hidefilter2: true,
			hidefilter3: true,
			hidefilter4: false,
			hidefilter5: false,
			hidefilter6: false
		} );
		// All filters of the group are selected == this is the same as not selecting any
		assert.deepEqual(
			model.getParametersFromFilters(),
			{
				// Group 1 (all selected, all false)
				hidefilter1: 0,
				hidefilter2: 0,
				hidefilter3: 0,
				// Group 2 (nothing is selected, all false)
				hidefilter4: 0,
				hidefilter5: 0,
				hidefilter6: 0,
				group3: 'all'
			},
			'All filters selected in one "send_unselected_if_any" group returns all parameters falsy.'
		);

		// Select 1 filter from string_options
		model.toggleFiltersSelected( {
			filter7: true,
			filter8: false,
			filter9: false
		} );
		// All filters of the group are selected == this is the same as not selecting any
		assert.deepEqual(
			model.getParametersFromFilters(),
			{
				// Group 1 (all selected, all)
				hidefilter1: 0,
				hidefilter2: 0,
				hidefilter3: 0,
				// Group 2 (nothing is selected, all false)
				hidefilter4: 0,
				hidefilter5: 0,
				hidefilter6: 0,
				group3: 'filter7'
			},
			'One filter selected in "string_option" group returns that filter in the value.'
		);

		// Select 2 filters from string_options
		model.toggleFiltersSelected( {
			filter7: true,
			filter8: true,
			filter9: false
		} );
		// All filters of the group are selected == this is the same as not selecting any
		assert.deepEqual(
			model.getParametersFromFilters(),
			{
				// Group 1 (all selected, all)
				hidefilter1: 0,
				hidefilter2: 0,
				hidefilter3: 0,
				// Group 2 (nothing is selected, all false)
				hidefilter4: 0,
				hidefilter5: 0,
				hidefilter6: 0,
				group3: 'filter7,filter8'
			},
			'Two filters selected in "string_option" group returns those filters in the value.'
		);

		// Select 3 filters from string_options
		model.toggleFiltersSelected( {
			filter7: true,
			filter8: true,
			filter9: true
		} );
		// All filters of the group are selected == this is the same as not selecting any
		assert.deepEqual(
			model.getParametersFromFilters(),
			{
				// Group 1 (all selected, all)
				hidefilter1: 0,
				hidefilter2: 0,
				hidefilter3: 0,
				// Group 2 (nothing is selected, all false)
				hidefilter4: 0,
				hidefilter5: 0,
				hidefilter6: 0,
				group3: 'all'
			},
			'All filters selected in "string_option" group returns \'all\'.'
		);

	} );

	QUnit.test( 'getFiltersFromParameters', function ( assert ) {
		var definition = {
				group1: {
					title: 'Group 1',
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hidefilter1',
							label: 'Show filter 1',
							description: 'Description of Filter 1 in Group 1',
							default: true
						},
						{
							name: 'hidefilter2',
							label: 'Show filter 2',
							description: 'Description of Filter 2 in Group 1'
						},
						{
							name: 'hidefilter3',
							label: 'Show filter 3',
							description: 'Description of Filter 3 in Group 1',
							default: true
						}
					]
				},
				group2: {
					title: 'Group 2',
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hidefilter4',
							label: 'Show filter 4',
							description: 'Description of Filter 1 in Group 2'
						},
						{
							name: 'hidefilter5',
							label: 'Show filter 5',
							description: 'Description of Filter 2 in Group 2',
							default: true
						},
						{
							name: 'hidefilter6',
							label: 'Show filter 6',
							description: 'Description of Filter 3 in Group 2'
						}
					]
				},
				group3: {
					title: 'Group 3',
					type: 'string_options',
					separator: ',',
					filters: [
						{
							name: 'filter7',
							label: 'Group 3: Filter 1',
							description: 'Description of Filter 1 in Group 3'
						},
						{
							name: 'filter8',
							label: 'Group 3: Filter 2',
							description: 'Description of Filter 2 in Group 3',
							default: true
						},
						{
							name: 'filter9',
							label: 'Group 3: Filter 3',
							description: 'Description of Filter 3 in Group 3'
						}
					]
				}
			},
			defaultFilterRepresentation = {
				// Group 1 and 2, "send_unselected_if_any", the values of the filters are "flipped" from the values of the parameters
				hidefilter1: false,
				hidefilter2: true,
				hidefilter3: false,
				hidefilter4: true,
				hidefilter5: false,
				hidefilter6: true,
				// Group 3, "string_options", default values correspond to parameters and filters
				filter7: false,
				filter8: true,
				filter9: false
			},
			model = new mw.rcfilters.dm.FiltersViewModel();

		model.initializeFilters( definition );

		// Empty query = only default values
		assert.deepEqual(
			model.getFiltersFromParameters( {} ),
			defaultFilterRepresentation,
			'Empty parameter query results in filters in initial default state'
		);

		assert.deepEqual(
			model.getFiltersFromParameters( {
				hidefilter2: '1'
			} ),
			$.extend( {}, defaultFilterRepresentation, {
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: false // The text is "show filter 3"
			} ),
			'One truthy parameter in a group whose other parameters are true by default makes the rest of the filters in the group false (unchecked)'
		);

		assert.deepEqual(
			model.getFiltersFromParameters( {
				hidefilter1: '1',
				hidefilter2: '1',
				hidefilter3: '1'
			} ),
			$.extend( {}, defaultFilterRepresentation, {
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: false // The text is "show filter 3"
			} ),
			'All paremeters in the same \'send_unselected_if_any\' group false is equivalent to none are truthy (checked) in the interface'
		);

		// The ones above don't update the model, so we have a clean state.
		// getFiltersFromParameters is stateless; any change is unaffected by the current state
		// This test is demonstrating wrong usage of the method;
		// We should be aware that getFiltersFromParameters is stateless,
		// so each call gives us a filter state that only reflects the query given.
		// This means that the two calls to toggleFiltersSelected() below collide.
		// The result of the first is overridden by the result of the second,
		// since both get a full state object from getFiltersFromParameters that **only** relates
		// to the input it receives.
		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				hidefilter1: '1'
			} )
		);

		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				hidefilter6: '1'
			} )
		);

		// The result here is ignoring the first toggleFiltersSelected call
		// We should receive default values + hidefilter6 as false
		assert.deepEqual(
			model.getSelectedState(),
			$.extend( {}, defaultFilterRepresentation, {
				hidefilter5: false,
				hidefilter6: false
			} ),
			'getFiltersFromParameters does not care about previous or existing state.'
		);

		// Reset
		model = new mw.rcfilters.dm.FiltersViewModel();
		model.initializeFilters( definition );

		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				hidefilter1: '0'
			} )
		);
		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				hidefilter1: '1'
			} )
		);

		// Simulates minor edits being hidden in preferences, then unhidden via URL
		// override.
		assert.deepEqual(
			model.getSelectedState(),
			defaultFilterRepresentation,
			'After checking and then unchecking a \'send_unselected_if_any\' filter (without touching other filters in that group), results are default'
		);

		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				group3: 'filter7'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			$.extend( {}, defaultFilterRepresentation, {
				filter7: true,
				filter8: false,
				filter9: false
			} ),
			'A \'string_options\' parameter containing 1 value, results in the corresponding filter as checked'
		);

		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				group3: 'filter7,filter8'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			$.extend( {}, defaultFilterRepresentation, {
				filter7: true,
				filter8: true,
				filter9: false
			} ),
			'A \'string_options\' parameter containing 2 values, results in both corresponding filters as checked'
		);

		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				group3: 'filter7,filter8,filter9'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			$.extend( {}, defaultFilterRepresentation, {
				filter7: false,
				filter8: false,
				filter9: false
			} ),
			'A \'string_options\' parameter containing all values, results in all filters of the group as unchecked.'
		);

		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				group3: 'filter7,all,filter9'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			$.extend( {}, defaultFilterRepresentation, {
				filter7: false,
				filter8: false,
				filter9: false
			} ),
			'A \'string_options\' parameter containing the value \'all\', results in all filters of the group as unchecked.'
		);

		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				group3: 'filter7,foo,filter9'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			$.extend( {}, defaultFilterRepresentation, {
				filter7: true,
				filter8: false,
				filter9: true
			} ),
			'A \'string_options\' parameter containing an invalid value, results in the invalid value ignored and the valid corresponding filters checked.'
		);
	} );

	QUnit.test( 'sanitizeStringOptionGroup', function ( assert ) {
		var definition = {
				group1: {
					title: 'Group 1',
					type: 'string_options',
					filters: [
						{
							name: 'filter1',
							label: 'Show filter 1',
							description: 'Description of Filter 1 in Group 1'
						},
						{
							name: 'filter2',
							label: 'Show filter 2',
							description: 'Description of Filter 2 in Group 1'
						},
						{
							name: 'filter3',
							label: 'Show filter 3',
							description: 'Description of Filter 3 in Group 1'
						}
					]
				}
			},
			model = new mw.rcfilters.dm.FiltersViewModel();

		model.initializeFilters( definition );

		assert.deepEqual(
			model.sanitizeStringOptionGroup( 'group1', [ 'filter1', 'filter1', 'filter2' ] ),
			[ 'filter1', 'filter2' ],
			'Remove duplicate values'
		);

		assert.deepEqual(
			model.sanitizeStringOptionGroup( 'group1', [ 'filter1', 'foo', 'filter2' ] ),
			[ 'filter1', 'filter2' ],
			'Remove invalid values'
		);

		assert.deepEqual(
			model.sanitizeStringOptionGroup( 'group1', [ 'filter1', 'all', 'filter2' ] ),
			[ 'all' ],
			'If any value is "all", the only value is "all".'
		);
	} );

	QUnit.test( 'setFiltersToDefaults', function ( assert ) {
		var definition = {
				group1: {
					title: 'Group 1',
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hidefilter1',
							label: 'Show filter 1',
							description: 'Description of Filter 1 in Group 1',
							default: true
						},
						{
							name: 'hidefilter2',
							label: 'Show filter 2',
							description: 'Description of Filter 2 in Group 1'
						},
						{
							name: 'hidefilter3',
							label: 'Show filter 3',
							description: 'Description of Filter 3 in Group 1',
							default: true
						}
					]
				},
				group2: {
					title: 'Group 2',
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'hidefilter4',
							label: 'Show filter 4',
							description: 'Description of Filter 1 in Group 2'
						},
						{
							name: 'hidefilter5',
							label: 'Show filter 5',
							description: 'Description of Filter 2 in Group 2',
							default: true
						},
						{
							name: 'hidefilter6',
							label: 'Show filter 6',
							description: 'Description of Filter 3 in Group 2'
						}
					]
				}
			},
			defaultFilterRepresentation = {
				// Group 1 and 2, "send_unselected_if_any", the values of the filters are "flipped" from the values of the parameters
				hidefilter1: false,
				hidefilter2: true,
				hidefilter3: false,
				hidefilter4: true,
				hidefilter5: false,
				hidefilter6: true
			},
			model = new mw.rcfilters.dm.FiltersViewModel();

		model.initializeFilters( definition );

		assert.deepEqual(
			model.getSelectedState(),
			{
				hidefilter1: false,
				hidefilter2: false,
				hidefilter3: false,
				hidefilter4: false,
				hidefilter5: false,
				hidefilter6: false
			},
			'Initial state: default filters are not selected (controller selects defaults explicitly).'
		);

		model.toggleFiltersSelected( {
			hidefilter1: false,
			hidefilter3: false
		} );

		model.setFiltersToDefaults();

		assert.deepEqual(
			model.getSelectedState(),
			defaultFilterRepresentation,
			'Changing values of filters and then returning to defaults still results in default filters being selected.'
		);
	} );

	QUnit.test( 'Filter interaction: subsets', function ( assert ) {
		var definition = {
				group1: {
					title: 'Group 1',
					type: 'string_options',
					filters: [
						{
							name: 'filter1',
							label: 'Show filter 1',
							description: 'Description of Filter 1 in Group 1',
							subset: [ 'filter2', 'filter5' ]
						},
						{
							name: 'filter2',
							label: 'Show filter 2',
							description: 'Description of Filter 2 in Group 1'
						},
						{
							name: 'filter3',
							label: 'Show filter 3',
							description: 'Description of Filter 3 in Group 1'
						}
					]
				},
				group2: {
					title: 'Group 2',
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'filter4',
							label: 'Show filter 4',
							description: 'Description of Filter 1 in Group 2',
							subset: [ 'filter3', 'filter5' ]
						},
						{
							name: 'filter5',
							label: 'Show filter 5',
							description: 'Description of Filter 2 in Group 2'
						},
						{
							name: 'filter6',
							label: 'Show filter 6',
							description: 'Description of Filter 3 in Group 2'
						}
					]
				}
			},
			baseFullState = {
				filter1: { selected: false, conflicted: false, included: false },
				filter2: { selected: false, conflicted: false, included: false },
				filter3: { selected: false, conflicted: false, included: false },
				filter4: { selected: false, conflicted: false, included: false },
				filter5: { selected: false, conflicted: false, included: false },
				filter6: { selected: false, conflicted: false, included: false }
			},
			model = new mw.rcfilters.dm.FiltersViewModel();

		model.initializeFilters( definition );
		// Select a filter that has subset with another filter
		model.toggleFiltersSelected( {
			filter1: true
		} );

		model.reassessFilterInteractions( model.getItemByName( 'filter1' ) );
		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullState, {
				filter1: { selected: true },
				filter2: { included: true },
				filter5: { included: true }
			} ),
			'Filters with subsets are represented in the model.'
		);

		// Select another filter that has a subset with the same previous filter
		model.toggleFiltersSelected( {
			filter4: true
		} );
		model.reassessFilterInteractions( model.getItemByName( 'filter4' ) );
		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullState, {
				filter1: { selected: true },
				filter2: { included: true },
				filter3: { included: true },
				filter4: { selected: true },
				filter5: { included: true }
			} ),
			'Filters that have multiple subsets are represented.'
		);

		// Remove one filter (but leave the other) that affects filter2
		model.toggleFiltersSelected( {
			filter1: false
		} );
		model.reassessFilterInteractions( model.getItemByName( 'filter1' ) );
		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullState, {
				filter2: { included: false },
				filter3: { included: true },
				filter4: { selected: true },
				filter5: { included: true }
			} ),
			'Removing a filter only un-includes its subset if there is no other filter affecting.'
		);

		model.toggleFiltersSelected( {
			filter4: false
		} );
		model.reassessFilterInteractions( model.getItemByName( 'filter4' ) );
		assert.deepEqual(
			model.getFullState(),
			baseFullState,
			'Removing all supersets also un-includes the subsets.'
		);
	} );

	QUnit.test( 'Filter interaction: full coverage', function ( assert ) {
		var definition = {
				group1: {
					title: 'Group 1',
					type: 'string_options',
					fullCoverage: false,
					filters: [
						{ name: 'filter1' },
						{ name: 'filter2' },
						{ name: 'filter3' }
					]
				},
				group2: {
					title: 'Group 2',
					type: 'send_unselected_if_any',
					fullCoverage: true,
					filters: [
						{ name: 'filter4' },
						{ name: 'filter5' },
						{ name: 'filter6' }
					]
				}
			},
			model = new mw.rcfilters.dm.FiltersViewModel(),
			isCapsuleItemMuted = function ( filterName ) {
				var itemModel = model.getItemByName( filterName ),
					groupModel = itemModel.getGroupModel();

				// This is the logic inside the capsule widget
				return (
					// The capsule item widget only appears if the item is selected
					itemModel.isSelected() &&
					// Muted state is only valid if group is full coverage and all items are selected
					groupModel.isFullCoverage() && groupModel.areAllSelected()
				);
			},
			getCurrentItemsMutedState = function () {
				return {
					filter1: isCapsuleItemMuted( 'filter1' ),
					filter2: isCapsuleItemMuted( 'filter2' ),
					filter3: isCapsuleItemMuted( 'filter3' ),
					filter4: isCapsuleItemMuted( 'filter4' ),
					filter5: isCapsuleItemMuted( 'filter5' ),
					filter6: isCapsuleItemMuted( 'filter6' )
				};
			},
			baseMuteState = {
				filter1: false,
				filter2: false,
				filter3: false,
				filter4: false,
				filter5: false,
				filter6: false
			};

		model.initializeFilters( definition );

		// Starting state, no selection, all items are non-muted
		assert.deepEqual(
			getCurrentItemsMutedState(),
			baseMuteState,
			'No selection - all items are non-muted'
		);

		// Select most (but not all) items in each group
		model.toggleFiltersSelected( {
			filter1: true,
			filter2: true,
			filter4: true,
			filter5: true
		} );

		// Both groups have multiple (but not all) items selected, all items are non-muted
		assert.deepEqual(
			getCurrentItemsMutedState(),
			baseMuteState,
			'Not all items in the group selected - all items are non-muted'
		);

		// Select all items in 'fullCoverage' group (group2)
		model.toggleFiltersSelected( {
			filter6: true
		} );

		// Group2 (full coverage) has all items selected, all its items are muted
		assert.deepEqual(
			getCurrentItemsMutedState(),
			$.extend( {}, baseMuteState, {
				filter4: true,
				filter5: true,
				filter6: true
			} ),
			'All items in \'full coverage\' group are selected - all items in the group are muted'
		);

		// Select all items in non 'fullCoverage' group (group1)
		model.toggleFiltersSelected( {
			filter3: true
		} );

		// Group1 (full coverage) has all items selected, no items in it are muted (non full coverage)
		assert.deepEqual(
			getCurrentItemsMutedState(),
			$.extend( {}, baseMuteState, {
				filter4: true,
				filter5: true,
				filter6: true
			} ),
			'All items in a non \'full coverage\' group are selected - none of the items in the group are muted'
		);

		// Uncheck an item from each group
		model.toggleFiltersSelected( {
			filter3: false,
			filter5: false
		} );
		assert.deepEqual(
			getCurrentItemsMutedState(),
			baseMuteState,
			'Not all items in the group are checked - all items are non-muted regardless of group coverage'
		);
	} );

	QUnit.test( 'Filter interaction: conflicts', function ( assert ) {
		var definition = {
				group1: {
					title: 'Group 1',
					type: 'string_options',
					filters: [
						{
							name: 'filter1',
							conflicts: [
								{ group: 'group2' }
							]
						},
						{
							name: 'filter2',
							conflicts: [
								{ group: 'group2', filter: 'filter6' }
							]
						},
						{
							name: 'filter3'
						}
					]
				},
				group2: {
					title: 'Group 2',
					type: 'send_unselected_if_any',
					conflicts: [
						{ group: 'group1', filter: 'filter1' }
					],
					filters: [
						{
							name: 'filter4'
						},
						{
							name: 'filter5'
						},
						{
							name: 'filter6',
							conflicts: [
								{ group: 'group1', filter: 'filter2' }
							]
						}
					]
				}
			},
			baseFullState = {
				filter1: { selected: false, conflicted: false, included: false },
				filter2: { selected: false, conflicted: false, included: false },
				filter3: { selected: false, conflicted: false, included: false },
				filter4: { selected: false, conflicted: false, included: false },
				filter5: { selected: false, conflicted: false, included: false },
				filter6: { selected: false, conflicted: false, included: false }
			},
			model = new mw.rcfilters.dm.FiltersViewModel();

		model.initializeFilters( definition );

		assert.deepEqual(
			model.getFullState(),
			baseFullState,
			'Initial state: no conflicts because no selections.'
		);

		// Select a filter that has a conflict with an entire group
		model.toggleFiltersSelected( {
			filter1: true // conflicts: entire of group 2 ( filter4, filter5, filter6)
		} );

		model.reassessFilterInteractions( model.getItemByName( 'filter1' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullState, {
				filter1: { selected: true },
				filter4: { conflicted: true },
				filter5: { conflicted: true },
				filter6: { conflicted: true }
			} ),
			'Selecting a filter that conflicts with a group sets all the conflicted group items as "conflicted".'
		);

		// Select one of the conflicts (both filters are now conflicted and selected)
		model.toggleFiltersSelected( {
			filter4: true // conflicts: filter 1
		} );
		model.reassessFilterInteractions( model.getItemByName( 'filter4' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullState, {
				filter1: { selected: true, conflicted: true },
				filter4: { selected: true, conflicted: true },
				filter5: { conflicted: true },
				filter6: { conflicted: true }
			} ),
			'Selecting a conflicting filter inside a group, sets both sides to conflicted and selected.'
		);

		// Reset
		model = new mw.rcfilters.dm.FiltersViewModel();
		model.initializeFilters( definition );

		// Select a filter that has a conflict with a specific filter
		model.toggleFiltersSelected( {
			filter2: true // conflicts: filter6
		} );

		model.reassessFilterInteractions( model.getItemByName( 'filter2' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullState, {
				filter2: { selected: true },
				filter6: { conflicted: true }
			} ),
			'Selecting a filter that conflicts with another filter sets the other as "conflicted".'
		);

		// Select the conflicting filter
		model.toggleFiltersSelected( {
			filter6: true // conflicts: filter2
		} );

		model.reassessFilterInteractions( model.getItemByName( 'filter6' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullState, {
				filter2: { selected: true, conflicted: true },
				filter6: { selected: true, conflicted: true },
				filter1: { conflicted: true } // This is added to the conflicts because filter6 is part of group2, who is in conflict with filter1
			} ),
			'Selecting a conflicting filter with an individual filter, sets both sides to conflicted and selected.'
		);

		// Now choose a non-conflicting filter from the group
		model.toggleFiltersSelected( {
			filter5: true
		} );

		model.reassessFilterInteractions( model.getItemByName( 'filter5' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullState, {
				filter2: { selected: true },
				filter6: { selected: true },
				filter5: { selected: true }
				// Filter1 is no longer in conflict because filter2 is selected
				// within its own group, which removes the conflict
			} ),
			'Selecting a non-conflicting filter within the group of a conflicting filter removes the conflicts.'
		);

		// Followup on the previous test, unselect filter2 so filter1 is once again conflicted
		model.toggleFiltersSelected( {
			filter2: false
		} );

		model.reassessFilterInteractions( model.getItemByName( 'filter5' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullState, {
				filter1: { conflicted: true },
				filter6: { selected: true },
				filter5: { selected: true }
			} ),
			'Unselecting an item that did not conflict returns the conflict state.'
		);

		// Followup #2: Now actually select filter1, and make everything conflicted
		model.toggleFiltersSelected( {
			filter1: true
		} );

		model.reassessFilterInteractions( model.getItemByName( 'filter5' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullState, {
				filter1: { selected: true, conflicted: true },
				filter6: { selected: true, conflicted: true },
				filter5: { selected: true, conflicted: true },
				filter4: { conflicted: true } // Not selected but conflicted because it's in group2
			} ),
			'Selecting an item that conflicts with a whole group makes all selections in that group conflicted.'
		);
	} );

	QUnit.test( 'Filter highlights', function ( assert ) {
		var definition = {
				group1: {
					title: 'Group 1',
					type: 'string_options',
					filters: [
						{ name: 'filter1', class: 'class1' },
						{ name: 'filter2', class: 'class2' },
						{ name: 'filter3', class: 'class3' },
						{ name: 'filter4', class: 'class4' },
						{ name: 'filter5', class: 'class5' },
						{ name: 'filter6' }
					]
				}
			},
			model = new mw.rcfilters.dm.FiltersViewModel();

		model.initializeFilters( definition );

		assert.ok(
			!model.isHighlightEnabled(),
			'Initially, highlight is disabled.'
		);

		model.toggleHighlight( true );
		assert.ok(
			model.isHighlightEnabled(),
			'Highlight is enabled on toggle.'
		);

		model.setHighlightColor( 'filter1', 'color1' );
		model.setHighlightColor( 'filter2', 'color2' );

		assert.deepEqual(
			model.getHighlightedItems().map( function ( item ) {
				return item.getName();
			} ),
			[
				'filter1',
				'filter2'
			],
			'Highlighted items are highlighted.'
		);

		assert.equal(
			model.getItemByName( 'filter1' ).getHighlightColor(),
			'color1',
			'Item highlight color is set.'
		);

		model.setHighlightColor( 'filter1', 'color1changed' );
		assert.equal(
			model.getItemByName( 'filter1' ).getHighlightColor(),
			'color1changed',
			'Item highlight color is changed on setHighlightColor.'
		);

		model.clearHighlightColor( 'filter1' );
		assert.deepEqual(
			model.getHighlightedItems().map( function ( item ) {
				return item.getName();
			} ),
			[
				'filter2'
			],
			'Clear highlight from an item results in the item no longer being highlighted.'
		);

		// Reset
		model = new mw.rcfilters.dm.FiltersViewModel();
		model.initializeFilters( definition );

		model.setHighlightColor( 'filter1', 'color1' );
		model.setHighlightColor( 'filter2', 'color2' );
		model.setHighlightColor( 'filter3', 'color3' );

		assert.deepEqual(
			model.getHighlightedItems().map( function ( item ) {
				return item.getName();
			} ),
			[
				'filter1',
				'filter2',
				'filter3'
			],
			'Even if highlights are not enabled, the items remember their highlight state'
			// NOTE: When actually displaying the highlights, the UI checks whether
			// highlighting is generally active and then goes over the highlighted
			// items. The item models, however, and the view model in general, still
			// retains the knowledge about which filters have different colors, so we
			// can seamlessly return to the colors the user previously chose if they
			// reapply highlights.
		);

		// Reset
		model = new mw.rcfilters.dm.FiltersViewModel();
		model.initializeFilters( definition );

		model.setHighlightColor( 'filter1', 'color1' );
		model.setHighlightColor( 'filter6', 'color6' );

		assert.deepEqual(
			model.getHighlightedItems().map( function ( item ) {
				return item.getName();
			} ),
			[
				'filter1'
			],
			'Items without a specified class identifier are not highlighted.'
		);
	} );
}( mediaWiki, jQuery ) );
