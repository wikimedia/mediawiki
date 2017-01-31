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
					exclusionType: 'default',
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
			model = new mw.rcfilters.dm.FiltersViewModel();

		model.initializeFilters( definition );

		assert.deepEqual(
			model.getFullState(),
			{
				// Group 1
				hidefilter1: { selected: true, active: true },
				hidefilter2: { selected: false, active: true },
				hidefilter3: { selected: true, active: true },
				// Group 2
				hidefilter4: { selected: false, active: true },
				hidefilter5: { selected: true, active: true },
				hidefilter6: { selected: false, active: true },
			},
			'Initial state: all filters are active, and select states are default.'
		);

		// Default behavior for 'exclusion' type with only 1 item selected, means that:
		// - The items in the same group that are *not* selected are *not* active
		// - Items in other groups are unaffected (all active)
		model.updateFilters( {
			hidefilter1: false,
			hidefilter2: false,
			hidefilter3: false,
			hidefilter4: false,
			hidefilter5: false,
			hidefilter6: true
		} );
		assert.deepEqual(
			model.getFullState(),
			{
				// Group 1: not affected
				hidefilter1: { selected: false, active: true },
				hidefilter2: { selected: false, active: true },
				hidefilter3: { selected: false, active: true },
				// Group 2: affected
				hidefilter4: { selected: false, active: false },
				hidefilter5: { selected: false, active: false },
				hidefilter6: { selected: true, active: true },
			},
			'Default exclusion behavior with 1 item selected in the group.'
		);

		// Default behavior for 'exclusion' type with multiple items selected, but not all, means that:
		// - The items in the same group that are *not* selected are *not* active
		// - Items in other groups are unaffected (all active)
		model.updateFilters( {
			// Literally updating filters to create a clean state
			hidefilter1: false,
			hidefilter2: false,
			hidefilter3: false,
			hidefilter4: false,
			hidefilter5: true,
			hidefilter6: true
		} );
		assert.deepEqual(
			model.getFullState(),
			{
				// Group 1: not affected
				hidefilter1: { selected: false, active: true },
				hidefilter2: { selected: false, active: true },
				hidefilter3: { selected: false, active: true },
				// Group 2: affected
				hidefilter4: { selected: false, active: false },
				hidefilter5: { selected: true, active: true },
				hidefilter6: { selected: true, active: true },
			},
			'Default exclusion behavior with multiple items (but not all) selected in the group.'
		);

		// Default behavior for 'exclusion' type with all items in the group selected, means that:
		// - All items in the group are NOT active
		// - Items in other groups are unaffected (all active)
		model.updateFilters( {
			// Literally updating filters to create a clean state
			hidefilter1: false,
			hidefilter2: false,
			hidefilter3: false,
			hidefilter4: true,
			hidefilter5: true,
			hidefilter6: true
		} );
		assert.deepEqual(
			model.getFullState(),
			{
				// Group 1: not affected
				hidefilter1: { selected: false, active: true },
				hidefilter2: { selected: false, active: true },
				hidefilter3: { selected: false, active: true },
				// Group 2: affected
				hidefilter4: { selected: true, active: false },
				hidefilter5: { selected: true, active: false },
				hidefilter6: { selected: true, active: false },
			},
			'Default exclusion behavior with all items in the group.'
		);
	} );

	QUnit.test( 'reapplyActiveFilters - "explicit" exclusion rules', function ( assert ) {
		var definition = {
				group1: {
					title: 'Group 1',
					type: 'send_unselected_if_any',
					exclusionType: 'explicit',
					filters: [
						{
							name: 'filter1',
							excludes: [ 'filter2', 'filter3' ],
							label: 'Show filter 1',
							description: 'Description of Filter 1 in Group 1'
						},
						{
							name: 'filter2',
							excludes: [ 'filter3' ],
							label: 'Show filter 2',
							description: 'Description of Filter 2 in Group 1'
						},
						{
							name: 'filter3',
							label: 'Show filter 3',
							excludes: [ 'filter1' ],
							description: 'Description of Filter 3 in Group 1'
						},
						{
							name: 'filter4',
							label: 'Show filter 4',
							description: 'Description of Filter 4 in Group 1'
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

		assert.deepEqual(
			model.getFullState(),
			{
				filter1: { selected: false, active: true },
				filter2: { selected: false, active: true },
				filter3: { selected: false, active: true },
				filter4: { selected: false, active: true }
			},
			'Initial state: all filters are active.'
		);

		// "Explicit" behavior for 'exclusion' with one item checked:
		// - Items in the 'excluded' list of the selected filter are inactive
		model.updateFilters( {
			// Literally updating filters to create a clean state
			filter1: true, // Excludes 'hidefilter2', 'hidefilter3'
			filter2: false, // Excludes 'hidefilter3'
			filter3: false, // Excludes 'hidefilter1'
			filter4: false // No exclusion list
		} );
		assert.deepEqual(
			model.getFullState(),
			{
				filter1: { selected: true, active: true },
				filter2: { selected: false, active: false },
				filter3: { selected: false, active: false },
				filter4: { selected: false, active: true }
			},
			'"Explicit" exclusion behavior with one item selected that has an exclusion list.'
		);

		// "Explicit" behavior for 'exclusion' with two item checked:
		// - Items in the 'excluded' list of each of the selected filter are inactive
		model.updateFilters( {
			// Literally updating filters to create a clean state
			filter1: true, // Excludes 'hidefilter2', 'hidefilter3'
			filter2: false, // Excludes 'hidefilter3'
			filter3: true, // Excludes 'hidefilter1'
			filter4: false // No exclusion list
		} );
		assert.deepEqual(
			model.getFullState(),
			{
				filter1: { selected: true, active: false },
				filter2: { selected: false, active: false },
				filter3: { selected: true, active: false },
				filter4: { selected: false, active: true }
			},
			'"Explicit" exclusion behavior with two selected items that both have an exclusion list.'
		);

		// "Explicit behavior" with two filters that exclude the same item

		// Two filters selected, both exclude 'hidefilter3'
		model.updateFilters( {
			// Literally updating filters to create a clean state
			filter1: true, // Excludes 'hidefilter2', 'hidefilter3'
			filter2: true, // Excludes 'hidefilter3'
			filter3: false, // Excludes 'hidefilter1'
			filter4: false // No exclusion list
		} );
		assert.deepEqual(
			model.getFullState(),
			{
				filter1: { selected: true, active: true },
				filter2: { selected: true, active: false }, // Excluded by filter1
				filter3: { selected: false, active: false }, // Excluded by both filter1 and filter2
				filter4: { selected: false, active: true }
			},
			'"Explicit" exclusion behavior with two selected items that both exclude another item.'
		);

		// Unselect filter2: filter3 should still be excluded, because filter1 excludes it and is selected
		model.updateFilters( {
			filter2: false, // Excludes 'hidefilter3'
		} );
		assert.deepEqual(
			model.getFullState(),
			{
				filter1: { selected: true, active: true },
				filter2: { selected: false, active: false }, // Excluded by filter1
				filter3: { selected: false, active: false }, // Still excluded by filter1
				filter4: { selected: false, active: true }
			},
			'"Explicit" exclusion behavior unselecting one item that excludes another item, that is being excluded by a third active item.'
		);

		// Unselect filter1: filter3 should now be active, since both filters that exclude it are unselected
		model.updateFilters( {
			filter1: false, // Excludes 'hidefilter3' and 'hidefilter2'
		} );
		assert.deepEqual(
			model.getFullState(),
			{
				filter1: { selected: false, active: true },
				filter2: { selected: false, active: true }, // No longer excluded by filter1
				filter3: { selected: false, active: true }, // No longer excluded by either filter1 nor filter2
				filter4: { selected: false, active: true }
			},
			'"Explicit" exclusion behavior unselecting both items that excluded the same third item.'
		);

	} );
}( mediaWiki, jQuery ) );
