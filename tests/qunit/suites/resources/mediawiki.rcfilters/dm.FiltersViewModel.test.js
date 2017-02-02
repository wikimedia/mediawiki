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

		model.updateFilters( {
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
				}
			},
			model = new mw.rcfilters.dm.FiltersViewModel();

		model.initializeFilters( definition );

		matches = model.findMatches( 'group 1' );
		assert.equal(
			matches.group1.length,
			2,
			'findMatches finds correct group with correct number of results'
		);

		assert.deepEqual(
			matches.group1.map( function ( item ) { return item.getName(); } ),
			[ 'group1filter1', 'group1filter2' ],
			'findMatches finds the correct items within a single group'
		);

		matches = model.findMatches( 'filter 1' );
		assert.ok(
			matches.group1.length === 1 && matches.group2.length === 1,
			'findMatches finds correct number of results in multiple groups'
		);

		assert.deepEqual(
			[
				matches.group1.map( function ( item ) { return item.getName(); } ),
				matches.group2.map( function ( item ) { return item.getName(); } )
			],
			[
				[ 'group1filter1' ],
				[ 'group2filter1' ]
			],
			'findMatches finds the correct items within multiple groups'
		);

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
				group3: 'all',
			},
			'Unselected filters return all parameters falsey or \'all\'.'
		);

		// Select 1 filter
		model.updateFilters( {
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
		model.updateFilters( {
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
		model.updateFilters( {
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
		model.updateFilters( {
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
		model.updateFilters( {
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
		model.updateFilters( {
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
		// This means that the two calls to updateFilters() below collide.
		// The result of the first is overridden by the result of the second,
		// since both get a full state object from getFiltersFromParameters that **only** relates
		// to the input it receives.
		model.updateFilters(
			model.getFiltersFromParameters( {
				hidefilter1: '1'
			} )
		);

		model.updateFilters(
			model.getFiltersFromParameters( {
				hidefilter6: '1'
			} )
		);

		// The result here is ignoring the first updateFilters call
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

		model.updateFilters(
			model.getFiltersFromParameters( {
				hidefilter1: '0'
			} )
		);
		model.updateFilters(
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

		model.updateFilters(
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

		model.updateFilters(
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

		model.updateFilters(
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

		model.updateFilters(
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

		model.updateFilters(
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
			defaultFilterRepresentation,
			'Initial state: default filters are selected.'
		);

		model.updateFilters( {
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
debugger;
		model.initializeFilters( definition );
		// Select a filter that has subset with another filter
		model.updateFilters( {
			filter1: true
		} );
		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullState, {
				filter1: { selected: true },
				filter2: { included: true },
				filter5: { included: true }
			} ),
			'Filters with subsets are represented in the model.'
		);

		// Select anothre filter that has a subset with the same previous filter
		model.updateFilters( {
			filter4: true
		} );
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
		model.updateFilters( {
			filter1: false
		} );
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

		model.updateFilters( {
			filter4: false
		} );
		assert.deepEqual(
			model.getFullState(),
			baseFullState,
			'Removing all supersets also un-includes the subsets.'
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
							conflicts: [ 'filter2', 'filter4' ]
						},
						{
							name: 'filter2',
							conflicts: [ 'filter6' ]
						},
						{
							name: 'filter3'
						}
					]
				},
				group2: {
					title: 'Group 2',
					type: 'send_unselected_if_any',
					filters: [
						{
							name: 'filter4'
						},
						{
							name: 'filter5',
							conflicts: [ 'filter3' ]
						},
						{
							name: 'filter6',
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

		// Select a filter that has a conflict with another
		model.updateFilters( {
			filter1: true
		} );
		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullState, {
				filter1: { selected: true },
				filter2: { conflicted: true },
				filter4: { conflicted: true },
			} ),
			'Selecting a filter set its conflicts list as "conflicted".'
		);

		// Select one of the conflicts (both filters are now conflicted and selected)
		model.updateFilters( {
			filter4: true
		} );
		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullState, {
				filter1: { selected: true, conflicted: true },
				filter2: { conflicted: true },
				filter4: { selected: true, conflicted: true },
			} ),
			'Selecting a conflicting filter sets both sides to conflicted and selected.'
		);

		// Select another filter from filter4 group (meaning, now filter4 no longer conflicts with filter1)
		model.updateFilters( {
			filter6: true
		} );
		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullState, {
				filter1: { selected: true, conflicted: false },
				filter2: { conflicted: true },
				filter4: { selected: true, conflicted: false },
				filter6: { selected: true, conflicted: false }
			} ),
			'Selecting a non-conflicting filter from a conflicting group removes the conflict'
		);
	} );

	// QUnit.test( 'Group interaction: full coverage', function ( assert ) {} );
}( mediaWiki, jQuery ) );
