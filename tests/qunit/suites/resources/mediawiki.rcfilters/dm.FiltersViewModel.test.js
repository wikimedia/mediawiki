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
				}
			},
			model = new mw.rcfilters.dm.FiltersViewModel();

		model.initializeFilters( definition );

		assert.ok(
			model.getItemByName( 'group1filter1' ) instanceof mw.rcfilters.dm.FilterItem &&
			model.getItemByName( 'group1filter2' ) instanceof mw.rcfilters.dm.FilterItem &&
			model.getItemByName( 'group2filter1' ) instanceof mw.rcfilters.dm.FilterItem &&
			model.getItemByName( 'group2filter2' ) instanceof mw.rcfilters.dm.FilterItem,
			'Filters instantiated and stored correctly'
		);

		assert.deepEqual(
			model.getState(),
			{
				group1filter1: false,
				group1filter2: false,
				group2filter1: false,
				group2filter2: false
			},
			'Initial state of filters'
		);

		model.updateFilters( {
			group1filter1: true,
			group2filter2: true
		} );
		assert.deepEqual(
			model.getState(),
			{
				group1filter1: true,
				group1filter2: false,
				group2filter1: false,
				group2filter2: true
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

	QUnit.test( 'getFiltersToParameters', function ( assert ) {
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
				}
			},
			model = new mw.rcfilters.dm.FiltersViewModel();

		model.initializeFilters( definition );

		// Starting with all filters unselected
		assert.deepEqual(
			model.getFiltersToParameters(),
			{
				hidefilter1: 0,
				hidefilter2: 0,
				hidefilter3: 0,
				hidefilter4: 0,
				hidefilter5: 0,
				hidefilter6: 0
			},
			'Unselected filters return all parameters falsey.'
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
			model.getFiltersToParameters(),
			{
				// Group 1 (one selected, the others are true)
				hidefilter1: 0,
				hidefilter2: 1,
				hidefilter3: 1,
				// Group 2 (nothing is selected, all false)
				hidefilter4: 0,
				hidefilter5: 0,
				hidefilter6: 0
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
			model.getFiltersToParameters(),
			{
				// Group 1 (two selected, the others are true)
				hidefilter1: 0,
				hidefilter2: 0,
				hidefilter3: 1,
				// Group 2 (nothing is selected, all false)
				hidefilter4: 0,
				hidefilter5: 0,
				hidefilter6: 0
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
			model.getFiltersToParameters(),
			{
				// Group 1 (all selected, all)
				hidefilter1: 0,
				hidefilter2: 0,
				hidefilter3: 0,
				// Group 2 (nothing is selected, all false)
				hidefilter4: 0,
				hidefilter5: 0,
				hidefilter6: 0
			},
			'All filters selected in one "send_unselected_if_any" group returns all parameters falsy.'
		);
	} );

	QUnit.test( 'getParametersToFilters', function ( assert ) {
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
				}
			},
			model = new mw.rcfilters.dm.FiltersViewModel();

		model.initializeFilters( definition );

		// Empty query = empty filter definition
		assert.deepEqual(
			model.getParametersToFilters( {} ),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: false, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false // The text is "show filter 6"
			},
			'Empty parameter query results in filters in initial state'
		);

		assert.deepEqual(
			model.getParametersToFilters( {
				hidefilter1: 1
			} ),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: true, // The text is "show filter 2"
				hidefilter3: true, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false // The text is "show filter 6"
			},
			'One falsey parameter in a group makes the rest of the filters in the group truthy (checked) in the interface'
		);

		assert.deepEqual(
			model.getParametersToFilters( {
				hidefilter1: 1,
				hidefilter2: 1
			} ),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: true, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false // The text is "show filter 6"
			},
			'Two falsey parameters in a group makes the rest of the filters in the group truthy (checked) in the interface'
		);

		assert.deepEqual(
			model.getParametersToFilters( {
				hidefilter1: 1,
				hidefilter2: 1,
				hidefilter3: 1
			} ),
			{
				// TODO: This will have to be represented as a different state, though.
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: false, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false // The text is "show filter 6"
			},
			'All paremeters in the same group false is equivalent to none are truthy (checked) in the interface'
		);

		// The ones above don't update the model, so we have a clean state.

		model.updateFilters(
			model.getParametersToFilters( {
				hidefilter1: 1
			} )
		);

		model.updateFilters(
			model.getParametersToFilters( {
				hidefilter3: 1
			} )
		);

		// 1 and 3 are separately unchecked via hide parameters, 2 should still be
		// checked.
		// This can simulate separate filters in the same group being hidden different
		// ways (e.g. preferences and URL).
		assert.deepEqual(
			model.getState(),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: true, // The text is "show filter 2"
				hidefilter3: false, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false // The text is "show filter 6"
			},
			'After unchecking 2 of 3 filters via separate updateFilters calls, only the remaining one is still checked.'
		);

		// Reset
		model = new mw.rcfilters.dm.FiltersViewModel();
		model.initializeFilters( definition );

		model.updateFilters(
			model.getParametersToFilters( {
				hidefilter1: 1
			} )
		);
		model.updateFilters(
			model.getParametersToFilters( {
				hidefilter1: 0
			} )
		);

		// Simulates minor edits being hidden in preferences, then unhidden via URL
		// override.
		assert.deepEqual(
			model.getState(),
			{
				hidefilter1: false, // The text is "show filter 1"
				hidefilter2: false, // The text is "show filter 2"
				hidefilter3: false, // The text is "show filter 3"
				hidefilter4: false, // The text is "show filter 4"
				hidefilter5: false, // The text is "show filter 5"
				hidefilter6: false // The text is "show filter 6"
			},
			'After unchecking then checking a filter (without touching other filters in that group), all are checked'
		);
	} );
}( mediaWiki, jQuery ) );
