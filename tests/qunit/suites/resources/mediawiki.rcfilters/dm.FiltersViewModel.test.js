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
							description: 'Description of Filter 1 in Group 1'
						},
						{
							name: 'hidefilter2',
							label: 'Show filter 2',
							description: 'Description of Filter 2 in Group 1'
						},
						{
							name: 'hidefilter3',
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
							name: 'hidefilter4',
							label: 'Show filter 4',
							description: 'Description of Filter 1 in Group 2'
						},
						{
							name: 'hidefilter5',
							label: 'Show filter 5',
							description: 'Description of Filter 2 in Group 2'
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

		// Empty query = empty filter definition
		assert.deepEqual(
			model.getFiltersFromParameters( {} ),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: false, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false, // The text is "show filter 6"
				filter7: false,
				filter8: false,
				filter9: false
			},
			'Empty parameter query results in filters in initial state'
		);

		assert.deepEqual(
			model.getFiltersFromParameters( {
				hidefilter1: "1"
			} ),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: true, // The text is "show filter 2"
				hidefilter3: true, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false, // The text is "show filter 6"
				filter7: false,
				filter8: false,
				filter9: false
			},
			'One falsey parameter in a group makes the rest of the filters in the group truthy (checked) in the interface'
		);

		assert.deepEqual(
			model.getFiltersFromParameters( {
				hidefilter1: "1",
				hidefilter2: "1"
			} ),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: true, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false, // The text is "show filter 6"
				filter7: false,
				filter8: false,
				filter9: false
			},
			'Two falsey parameters in a \'send_unselected_if_any\' group makes the rest of the filters in the group truthy (checked) in the interface'
		);

		assert.deepEqual(
			model.getFiltersFromParameters( {
				hidefilter1: "1",
				hidefilter2: "1",
				hidefilter3: "1"
			} ),
			{
				// TODO: This will have to be represented as a different state, though.
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: false, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false, // The text is "show filter 6"
				filter7: false,
				filter8: false,
				filter9: false
			},
			'All paremeters in the same \'send_unselected_if_any\' group false is equivalent to none are truthy (checked) in the interface'
		);

		// The ones above don't update the model, so we have a clean state.

		model.updateFilters(
			model.getFiltersFromParameters( {
				hidefilter1: "1"
			} )
		);

		model.updateFilters(
			model.getFiltersFromParameters( {
				hidefilter3: "1"
			} )
		);

		// 1 and 3 are separately unchecked via hide parameters, 2 should still be
		// checked.
		// This can simulate separate filters in the same group being hidden different
		// ways (e.g. preferences and URL).
		assert.deepEqual(
			model.getSelectedState(),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: true, // The text is "show filter 2"
				hidefilter3: false, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false, // The text is "show filter 6"
				filter7: false,
				filter8: false,
				filter9: false
			},
			'After unchecking 2 of 3 \'send_unselected_if_any\' filters via separate updateFilters calls, only the remaining one is still checked.'
		);

		// Reset
		model = new mw.rcfilters.dm.FiltersViewModel();
		model.initializeFilters( definition );

		model.updateFilters(
			model.getFiltersFromParameters( {
				hidefilter1: "1"
			} )
		);
		model.updateFilters(
			model.getFiltersFromParameters( {
				hidefilter1: "0"
			} )
		);

		// Simulates minor edits being hidden in preferences, then unhidden via URL
		// override.
		assert.deepEqual(
			model.getSelectedState(),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: false, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false, // The text is "show filter 6"
				filter7: false,
				filter8: false,
				filter9: false
			},
			'After unchecking then checking a \'send_unselected_if_any\' filter (without touching other filters in that group), all are checked'
		);

		model.updateFilters(
			model.getFiltersFromParameters( {
				group3: 'filter7'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: false, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false, // The text is "show filter 6"
				filter7: true,
				filter8: false,
				filter9: false
			},
			'A \'string_options\' parameter containing 1 value, results in the corresponding filter as checked'
		);

		model.updateFilters(
			model.getFiltersFromParameters( {
				group3: 'filter7,filter8'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: false, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false, // The text is "show filter 6"
				filter7: true,
				filter8: true,
				filter9: false
			},
			'A \'string_options\' parameter containing 2 values, results in both corresponding filters as checked'
		);

		model.updateFilters(
			model.getFiltersFromParameters( {
				group3: 'filter7,filter8,filter9'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: false, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false, // The text is "show filter 6"
				filter7: false,
				filter8: false,
				filter9: false
			},
			'A \'string_options\' parameter containing all values, results in all filters of the group as unchecked.'
		);

		model.updateFilters(
			model.getFiltersFromParameters( {
				group3: 'filter7,filter8,filter9'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: false, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false, // The text is "show filter 6"
				filter7: false,
				filter8: false,
				filter9: false
			},
			'A \'string_options\' parameter containing the value \'all\', results in all filters of the group as unchecked.'
		);

		model.updateFilters(
			model.getFiltersFromParameters( {
				group3: 'filter7,foo,filter9'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: false, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false, // The text is "show filter 6"
				filter7: true,
				filter8: false,
				filter9: true
			},
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

	QUnit.test( 'reapplyActiveFilters - "default" exclusion rules', function ( assert ) {
		var definition = {
				group1: {
					title: 'Group 1',
					type: 'send_unselected_if_any',
					exclusion_type: 'default',
					filters: [
						{
							name: 'hidefilter1',
							label: 'Show filter 1',
							description: 'Description of Filter 1 in Group 1'
						},
						{
							name: 'hidefilter2',
							label: 'Show filter 2',
							description: 'Description of Filter 2 in Group 1'
						},
						{
							name: 'hidefilter3',
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
							name: 'hidefilter4',
							label: 'Show filter 4',
							description: 'Description of Filter 1 in Group 2'
						},
						{
							name: 'hidefilter5',
							label: 'Show filter 5',
							description: 'Description of Filter 2 in Group 2'
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
				hidefilter1: { selected: false, active: true },
				hidefilter2: { selected: false, active: true },
				hidefilter3: { selected: false, active: true },
				// Group 2
				hidefilter4: { selected: false, active: true },
				hidefilter5: { selected: false, active: true },
				hidefilter6: { selected: false, active: true },
			},
			'Initial state: all filters are active.'
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
					exclusion_type: 'explicit',
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
	} );
}( mediaWiki, jQuery ) );
