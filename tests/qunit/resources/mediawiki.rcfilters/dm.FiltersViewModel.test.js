/* eslint-disable camelcase */
( function () {
	const rcfilters = require( 'mediawiki.rcfilters.filters.ui' );
	const filterDefinition = [ {
			name: 'group1',
			type: 'send_unselected_if_any',
			filters: [
				{
					name: 'filter1', label: 'group1filter1-label', description: 'group1filter1-desc',
					default: true,
					cssClass: 'filter1class',
					conflicts: [ { group: 'group2' } ],
					subset: [
						{
							group: 'group1',
							filter: 'filter2'
						},
						{
							group: 'group1',
							filter: 'filter3'
						}
					]
				},
				{
					name: 'filter2', label: 'group1filter2-label', description: 'group1filter2-desc',
					conflicts: [ { group: 'group2', filter: 'filter6' } ],
					cssClass: 'filter2class',
					subset: [
						{
							group: 'group1',
							filter: 'filter3'
						}
					]
				},
				// NOTE: This filter has no highlight!
				{ name: 'filter3', label: 'group1filter3-label', description: 'group1filter3-desc', default: true }
			]
		}, {
			name: 'group2',
			type: 'send_unselected_if_any',
			fullCoverage: true,
			conflicts: [ { group: 'group1', filter: 'filter1' } ],
			filters: [
				{ name: 'filter4', label: 'group2filter4-label', description: 'group2filter4-desc', cssClass: 'filter4class' },
				{ name: 'filter5', label: 'group2filter5-label', description: 'group2filter5-desc', default: true, cssClass: 'filter5class' },
				{
					name: 'filter6', label: 'group2filter6-label', description: 'group2filter6-desc', cssClass: 'filter6class',
					conflicts: [ { group: 'group1', filter: 'filter2' } ]
				}
			]
		}, {
			name: 'group3',
			type: 'string_options',
			separator: ',',
			default: 'filter8',
			filters: [
				{ name: 'filter7', label: 'group3filter7-label', description: 'group3filter7-desc', cssClass: 'filter7class' },
				{ name: 'filter8', label: 'group3filter8-label', description: 'group3filter8-desc', cssClass: 'filter8class' },
				{ name: 'filter9', label: 'group3filter9-label', description: 'group3filter9-desc', cssClass: 'filter9class' }
			]
		}, {
			name: 'group4',
			type: 'single_option',
			hidden: true,
			default: 'option2',
			filters: [
				// NOTE: The entire group has no highlight supported
				{ name: 'option1', label: 'group4option1-label', description: 'group4option1-desc' },
				{ name: 'option2', label: 'group4option2-label', description: 'group4option2-desc' },
				{ name: 'option3', label: 'group4option3-label', description: 'group4option3-desc' }
			]
		}, {
			name: 'group5',
			type: 'single_option',
			filters: [
				{ name: 'option1', label: 'group5option1-label', description: 'group5option1-desc', cssClass: 'group5opt1class' },
				{ name: 'option2', label: 'group5option2-label', description: 'group5option2-desc', cssClass: 'group5opt2class' },
				{ name: 'option3', label: 'group5option3-label', description: 'group5option3-desc', cssClass: 'group5opt3class' }
			]
		}, {
			name: 'group6',
			type: 'boolean',
			sticky: true,
			filters: [
				{ name: 'group6option1', label: 'group6option1-label', description: 'group6option1-desc', cssClass: 'group6opt1class' },
				{ name: 'group6option2', label: 'group6option2-label', description: 'group6option2-desc', default: true, cssClass: 'group6opt2class' },
				{ name: 'group6option3', label: 'group6option3-label', description: 'group6option3-desc', default: true, cssClass: 'group6opt3class' }
			]
		}, {
			name: 'group7',
			type: 'single_option',
			sticky: true,
			default: 'group7option2',
			filters: [
				{ name: 'group7option1', label: 'group7option1-label', description: 'group7option1-desc', cssClass: 'group7opt1class' },
				{ name: 'group7option2', label: 'group7option2-label', description: 'group7option2-desc', cssClass: 'group7opt2class' },
				{ name: 'group7option3', label: 'group7option3-label', description: 'group7option3-desc', cssClass: 'group7opt3class' }
			]
		} ],
		shortFilterDefinition = [ {
			name: 'group1',
			type: 'send_unselected_if_any',
			filters: [ { name: 'filter1' }, { name: 'filter2' } ]
		}, {
			name: 'group2',
			type: 'boolean',
			hidden: true,
			filters: [ { name: 'filter3' }, { name: 'filter4' } ]
		}, {
			name: 'group3',
			type: 'string_options',
			sticky: true,
			default: 'filter6',
			filters: [ { name: 'filter5' }, { name: 'filter6' }, { name: 'filter7' } ]
		} ],
		viewsDefinition = {
			namespaces: {
				label: 'Namespaces',
				trigger: ':',
				groups: [ {
					name: 'namespace',
					label: 'Namespaces',
					type: 'string_options',
					separator: ';',
					filters: [
						{ name: 0, label: 'Main', cssClass: 'namespace-0' },
						{ name: 1, label: 'Talk', cssClass: 'namespace-1' },
						{ name: 2, label: 'User', cssClass: 'namespace-2' },
						{ name: 3, label: 'User talk', cssClass: 'namespace-3' }
					]
				} ]
			}
		},
		defaultParameters = {
			filter1: '1',
			filter2: '0',
			filter3: '1',
			filter4: '0',
			filter5: '1',
			filter6: '0',
			group3: 'filter8',
			group4: 'option2',
			group5: 'option1',
			namespace: ''
		},
		baseParamRepresentation = {
			filter1: '0',
			filter2: '0',
			filter3: '0',
			filter4: '0',
			filter5: '0',
			filter6: '0',
			group3: '',
			group4: 'option2',
			group5: 'option1',
			group6option1: '0',
			group6option2: '1',
			group6option3: '1',
			group7: 'group7option2',
			namespace: ''
		},
		emptyParamRepresentation = {
			filter1: '0',
			filter2: '0',
			filter3: '0',
			filter4: '0',
			filter5: '0',
			filter6: '0',
			group3: '',
			group4: '',
			group5: '',
			group6option1: '0',
			group6option2: '0',
			group6option3: '0',
			group7: '',
			namespace: '',
			// Null highlights
			group1__filter1_color: null,
			group1__filter2_color: null,
			// group1__filter3_color: null, // Highlight isn't supported
			group2__filter4_color: null,
			group2__filter5_color: null,
			group2__filter6_color: null,
			group3__filter7_color: null,
			group3__filter8_color: null,
			group3__filter9_color: null,
			// group4__option1_color: null, // Highlight isn't supported
			// group4__option2_color: null, // Highlight isn't supported
			// group4__option3_color: null, // Highlight isn't supported
			group5__option1_color: null,
			group5__option2_color: null,
			group5__option3_color: null,
			group6__group6option1_color: null,
			group6__group6option2_color: null,
			group6__group6option3_color: null,
			group7__group7option1_color: null,
			group7__group7option2_color: null,
			group7__group7option3_color: null,
			namespace__0_color: null,
			namespace__1_color: null,
			namespace__2_color: null,
			namespace__3_color: null
		},
		baseFilterRepresentation = {
			group1__filter1: false,
			group1__filter2: false,
			group1__filter3: false,
			group2__filter4: false,
			group2__filter5: false,
			group2__filter6: false,
			group3__filter7: false,
			group3__filter8: false,
			group3__filter9: false,
			// The 'single_value' type of group can't have empty value; it's either
			// the default given or the first item that will get the truthy value
			group4__option1: false,
			group4__option2: true, // Default
			group4__option3: false,
			group5__option1: true, // No default set, first item is default value
			group5__option2: false,
			group5__option3: false,
			group6__group6option1: false,
			group6__group6option2: true,
			group6__group6option3: true,
			group7__group7option1: false,
			group7__group7option2: true,
			group7__group7option3: false,
			namespace__0: false,
			namespace__1: false,
			namespace__2: false,
			namespace__3: false
		},
		baseFullFilterState = {
			group1__filter1: { selected: false, conflicted: false, included: false },
			group1__filter2: { selected: false, conflicted: false, included: false },
			group1__filter3: { selected: false, conflicted: false, included: false },
			group2__filter4: { selected: false, conflicted: false, included: false },
			group2__filter5: { selected: false, conflicted: false, included: false },
			group2__filter6: { selected: false, conflicted: false, included: false },
			group3__filter7: { selected: false, conflicted: false, included: false },
			group3__filter8: { selected: false, conflicted: false, included: false },
			group3__filter9: { selected: false, conflicted: false, included: false },
			group4__option1: { selected: false, conflicted: false, included: false },
			group4__option2: { selected: true, conflicted: false, included: false },
			group4__option3: { selected: false, conflicted: false, included: false },
			group5__option1: { selected: true, conflicted: false, included: false },
			group5__option2: { selected: false, conflicted: false, included: false },
			group5__option3: { selected: false, conflicted: false, included: false },
			group6__group6option1: { selected: false, conflicted: false, included: false },
			group6__group6option2: { selected: true, conflicted: false, included: false },
			group6__group6option3: { selected: true, conflicted: false, included: false },
			group7__group7option1: { selected: false, conflicted: false, included: false },
			group7__group7option2: { selected: true, conflicted: false, included: false },
			group7__group7option3: { selected: false, conflicted: false, included: false },
			namespace__0: { selected: false, conflicted: false, included: false },
			namespace__1: { selected: false, conflicted: false, included: false },
			namespace__2: { selected: false, conflicted: false, included: false },
			namespace__3: { selected: false, conflicted: false, included: false }
		};

	QUnit.module( 'mediawiki.rcfilters - FiltersViewModel', QUnit.newMwEnvironment( {
		messages: {
			'group1filter1-label': 'Group 1: Filter 1 title',
			'group1filter1-desc': 'Description of Filter 1 in Group 1',
			'group1filter2-label': 'Group 1: Filter 2 title',
			'group1filter2-desc': 'Description of Filter 2 in Group 1',
			'group1filter3-label': 'Group 1: Filter 3',
			'group1filter3-desc': 'Description of Filter 3 in Group 1',

			'group2filter4-label': 'Group 2: Filter 4 title',
			'group2filter4-desc': 'Description of Filter 4 in Group 2',
			'group2filter5-label': 'Group 2: Filter 5',
			'group2filter5-desc': 'Description of Filter 5 in Group 2',
			'group2filter6-label': 'xGroup 2: Filter 6',
			'group2filter6-desc': 'Description of Filter 6 in Group 2'
		}
	} ) );

	QUnit.test( 'Setting up filters', ( assert ) => {
		const model = new rcfilters.dm.FiltersViewModel();

		model.initializeFilters( filterDefinition, viewsDefinition );

		// Test that all items were created
		assert.true(
			Object.keys( baseFilterRepresentation ).every( ( filterName ) => model.getItemByName( filterName ) instanceof rcfilters.dm.FilterItem ),
			'Filters instantiated and stored correctly'
		);

		assert.deepEqual(
			model.getSelectedState(),
			baseFilterRepresentation,
			'Initial state of filters'
		);

		model.toggleFiltersSelected( {
			group1__filter1: true,
			group2__filter5: true,
			group3__filter7: true
		} );
		assert.deepEqual(
			model.getSelectedState(),
			$.extend( true, {}, baseFilterRepresentation, {
				group1__filter1: true,
				group2__filter5: true,
				group3__filter7: true
			} ),
			'Updating filter states correctly'
		);
	} );

	QUnit.test( 'Default filters', ( assert ) => {
		const model = new rcfilters.dm.FiltersViewModel();

		model.initializeFilters( filterDefinition, viewsDefinition );

		// Empty query = only default values
		assert.deepEqual(
			model.getDefaultParams(),
			defaultParameters,
			'Default parameters are stored properly per filter and group (sticky groups are ignored)'
		);
	} );

	QUnit.test( 'Parameter minimal state', ( assert ) => {
		const model = new rcfilters.dm.FiltersViewModel(),
			cases = [
				{
					input: {},
					result: {},
					msg: 'Empty parameter representation produces an empty result'
				},
				{
					input: {
						filter1: '1',
						filter2: '0',
						filter3: '0',
						group3: '',
						group4: 'option2'
					},
					result: {
						filter1: '1',
						group4: 'option2'
					},
					msg: 'Mixed input results in only non-falsey values as result'
				},
				{
					input: {
						filter1: '0',
						filter2: '0',
						filter3: '0',
						group3: '',
						group4: '',
						group1__filter1_color: null
					},
					result: {},
					msg: 'An all-falsey input results in an empty result.'
				},
				{
					input: {
						filter1: '0',
						filter2: '0',
						filter3: '0',
						group3: '',
						group4: '',
						group1__filter1_color: 'c1'
					},
					result: {
						group1__filter1_color: 'c1'
					},
					msg: 'An all-falsey input with highlight params result in only the highlight param.'
				},
				{
					input: {
						group1__filter1_color: 'c1',
						group1__filter3_color: 'c3' // Not supporting highlights
					},
					result: {
						group1__filter1_color: 'c1'
					},
					msg: 'Unsupported highlights are removed.'
				}
			];

		model.initializeFilters( filterDefinition, viewsDefinition );

		cases.forEach( ( test ) => {
			assert.deepEqual(
				model.getMinimizedParamRepresentation( test.input ),
				test.result,
				test.msg
			);
		} );
	} );

	QUnit.test( 'Parameter states', ( assert ) => {
		// Some groups / params have their defaults immediately applied
		// to their state. These include single_option which can never
		// be empty, etc. These are these states:
		const appliedDefaultParameters = {
			group4: 'option2',
			group5: 'option1',
			// Sticky, their defaults apply immediately
			group6option2: '1',
			group6option3: '1',
			group7: 'group7option2'
		};
		let model = new rcfilters.dm.FiltersViewModel();

		model.initializeFilters( filterDefinition, viewsDefinition );
		assert.deepEqual(
			model.getEmptyParameterState(),
			emptyParamRepresentation,
			'Producing an empty parameter state'
		);

		model.toggleFiltersSelected( {
			group1__filter1: true,
			group3__filter7: true
		} );

		assert.deepEqual(
			model.getCurrentParameterState(),
			// appliedDefaultParams applies the default value to parameters
			// who must have an initial value to begin with, so we have to
			// take it into account in the current state
			$.extend( true, {}, appliedDefaultParameters, {
				filter2: '1',
				filter3: '1',
				group3: 'filter7'
			} ),
			'Producing a current parameter state'
		);

		// Reset
		model = new rcfilters.dm.FiltersViewModel();
		model.initializeFilters( filterDefinition, viewsDefinition );

		const parametersWithoutExcluded = $.extend( true, {}, appliedDefaultParameters );
		delete parametersWithoutExcluded.group7;
		delete parametersWithoutExcluded.group6option2;
		delete parametersWithoutExcluded.group6option3;

		assert.deepEqual(
			model.getCurrentParameterState( true ),
			parametersWithoutExcluded,
			'Producing a current clean parameter state without excluded filters'
		);
	} );

	QUnit.test( 'Cleaning up parameter states', ( assert ) => {
		const model = new rcfilters.dm.FiltersViewModel(),
			cases = [
				{
					input: {},
					result: {},
					msg: 'Empty parameter representation produces an empty result'
				},
				{
					input: {
						filter1: '1', // Regular (do not strip)
						group6option1: '1' // Sticky
					},
					result: { filter1: '1' },
					msg: 'Valid input strips all sticky params regardless of value'
				}
			];

		model.initializeFilters( filterDefinition, viewsDefinition );

		cases.forEach( ( test ) => {
			assert.deepEqual(
				model.removeStickyParams( test.input ),
				test.result,
				test.msg
			);
		} );

	} );

	QUnit.test( 'Finding matching filters', ( assert ) => {
		const testCases = [
				{
					query: 'group',
					expectedMatches: {
						group1: [ 'group1__filter1', 'group1__filter2', 'group1__filter3' ],
						group2: [ 'group2__filter4', 'group2__filter5' ]
					},
					reason: 'Finds filters starting with the query string'
				},
				{
					query: 'in Group 2',
					expectedMatches: {
						group2: [ 'group2__filter4', 'group2__filter5', 'group2__filter6' ]
					},
					reason: 'Finds filters containing the query string in their description'
				},
				{
					query: 'title',
					expectedMatches: {
						group1: [ 'group1__filter1', 'group1__filter2' ],
						group2: [ 'group2__filter4' ]
					},
					reason: 'Finds filters containing the query string in their group title'
				},
				{
					query: ':Main',
					expectedMatches: {
						namespace: [ 'namespace__0' ]
					},
					reason: 'Finds item in view when a prefix is used'
				},
				{
					query: ':group',
					expectedMatches: {},
					reason: 'Finds no results if using namespaces prefix (:) to search for filter title'
				}
			],
			model = new rcfilters.dm.FiltersViewModel(),
			extractNames = function ( matches ) {
				const result = {};
				for ( const groupName in matches ) {
					result[ groupName ] = matches[ groupName ].map( ( item ) => item.getName() );
				}
				return result;
			};

		model.initializeFilters( filterDefinition, viewsDefinition );

		testCases.forEach( ( testCase ) => {
			const matches = model.findMatches( testCase.query );
			assert.deepEqual(
				extractNames( matches ),
				testCase.expectedMatches,
				testCase.reason
			);
		} );

		const foundMatches = model.findMatches( 'foo' );
		assert.true(
			$.isEmptyObject( foundMatches ),
			'findMatches returns an empty object when no results found'
		);
	} );

	QUnit.test( 'getParametersFromFilters', ( assert ) => {
		let model = new rcfilters.dm.FiltersViewModel();

		model.initializeFilters( filterDefinition, viewsDefinition );

		// Starting with all filters unselected
		assert.deepEqual(
			model.getParametersFromFilters(),
			baseParamRepresentation,
			'Unselected filters return all parameters falsey or \'\'.'
		);

		// Select 1 filter
		model.toggleFiltersSelected( {
			group1__filter1: true
		} );
		// Only one filter in one group
		assert.deepEqual(
			model.getParametersFromFilters(),
			$.extend( true, {}, baseParamRepresentation, {
				// Group 1 (one selected, the others are true)
				filter2: '1',
				filter3: '1'
			} ),
			'One filter in one "send_unselected_if_any" group returns the other parameters truthy.'
		);

		// Select 2 filters
		model.toggleFiltersSelected( {
			group1__filter1: true,
			group1__filter2: true
		} );
		// Two selected filters in one group
		assert.deepEqual(
			model.getParametersFromFilters(),
			$.extend( true, {}, baseParamRepresentation, {
				// Group 1 (two selected, the other is true)
				filter3: '1'
			} ),
			'Two filters in one "send_unselected_if_any" group returns the other parameters truthy.'
		);

		// Select 3 filters
		model.toggleFiltersSelected( {
			group1__filter1: true,
			group1__filter2: true,
			group1__filter3: true
		} );
		// All filters of the group are selected == this is the same as not selecting any
		assert.deepEqual(
			model.getParametersFromFilters(),
			baseParamRepresentation,
			'All filters selected in one "send_unselected_if_any" group returns all parameters falsy.'
		);

		// Select 1 filter from string_options
		model.toggleFiltersSelected( {
			group3__filter7: true,
			group3__filter8: false,
			group3__filter9: false
		} );
		// All filters of the group are selected == this is the same as not selecting any
		assert.deepEqual(
			model.getParametersFromFilters(),
			$.extend( true, {}, baseParamRepresentation, {
				group3: 'filter7'
			} ),
			'One filter selected in "string_option" group returns that filter in the value.'
		);

		// Select 2 filters from string_options
		model.toggleFiltersSelected( {
			group3__filter7: true,
			group3__filter8: true,
			group3__filter9: false
		} );
		// All filters of the group are selected == this is the same as not selecting any
		assert.deepEqual(
			model.getParametersFromFilters(),
			$.extend( true, {}, baseParamRepresentation, {
				group3: 'filter7,filter8'
			} ),
			'Two filters selected in "string_option" group returns those filters in the value.'
		);

		// Select 3 filters from string_options
		model.toggleFiltersSelected( {
			group3__filter7: true,
			group3__filter8: true,
			group3__filter9: true
		} );
		// All filters of the group are selected == this is the same as not selecting any
		assert.deepEqual(
			model.getParametersFromFilters(),
			$.extend( true, {}, baseParamRepresentation, {
				group3: 'all'
			} ),
			'All filters selected in "string_option" group returns \'all\'.'
		);

		// Reset
		model = new rcfilters.dm.FiltersViewModel();
		model.initializeFilters( filterDefinition, viewsDefinition );

		// Select an option from single_option group
		model.toggleFiltersSelected( {
			group4__option2: true
		} );
		// All filters of the group are selected == this is the same as not selecting any
		assert.deepEqual(
			model.getParametersFromFilters(),
			$.extend( true, {}, baseParamRepresentation, {
				group4: 'option2'
			} ),
			'Selecting an option from "single_option" group returns that option as a value.'
		);

		// Select a different option from single_option group
		model.toggleFiltersSelected( {
			group4__option3: true
		} );
		// All filters of the group are selected == this is the same as not selecting any
		assert.deepEqual(
			model.getParametersFromFilters(),
			$.extend( true, {}, baseParamRepresentation, {
				group4: 'option3'
			} ),
			'Selecting a different option from "single_option" group changes the selection.'
		);
	} );

	QUnit.test( 'getParametersFromFilters (custom object)', ( assert ) => {
		// This entire test uses different base definition than the global one
		// on purpose, to verify that the values inserted as a custom object
		// are the ones we expect in return
		const model = new rcfilters.dm.FiltersViewModel(),
			definition = [ {
				name: 'group1',
				title: 'Group 1',
				type: 'send_unselected_if_any',
				filters: [
					{ name: 'hidefilter1', label: 'Hide filter 1', description: '' },
					{ name: 'hidefilter2', label: 'Hide filter 2', description: '' },
					{ name: 'hidefilter3', label: 'Hide filter 3', description: '' }
				]
			}, {
				name: 'group2',
				title: 'Group 2',
				type: 'send_unselected_if_any',
				filters: [
					{ name: 'hidefilter4', label: 'Hide filter 4', description: '' },
					{ name: 'hidefilter5', label: 'Hide filter 5', description: '' },
					{ name: 'hidefilter6', label: 'Hide filter 6', description: '' }
				]
			}, {
				name: 'group3',
				title: 'Group 3',
				type: 'string_options',
				separator: ',',
				filters: [
					{ name: 'filter7', label: 'Hide filter 7', description: '' },
					{ name: 'filter8', label: 'Hide filter 8', description: '' },
					{ name: 'filter9', label: 'Hide filter 9', description: '' }
				]
			}, {
				name: 'group4',
				title: 'Group 4',
				type: 'single_option',
				filters: [
					{ name: 'filter10', label: 'Hide filter 10', description: '' },
					{ name: 'filter11', label: 'Hide filter 11', description: '' },
					{ name: 'filter12', label: 'Hide filter 12', description: '' }
				]
			} ],
			baseResult = {
				hidefilter1: '0',
				hidefilter2: '0',
				hidefilter3: '0',
				hidefilter4: '0',
				hidefilter5: '0',
				hidefilter6: '0',
				group3: '',
				group4: ''
			},
			cases = [
				{
					// This is mocking the cases above, both
					// - 'Two filters in one "send_unselected_if_any" group returns the other parameters truthy.'
					// - 'Two filters selected in "string_option" group returns those filters in the value.'
					input: {
						group1__hidefilter1: true,
						group1__hidefilter2: true,
						group1__hidefilter3: false,
						group2__hidefilter4: false,
						group2__hidefilter5: false,
						group2__hidefilter6: false,
						group3__filter7: true,
						group3__filter8: true,
						group3__filter9: false
					},
					expected: $.extend( true, {}, baseResult, {
						// Group 1 (two selected, the others are true)
						hidefilter3: '1',
						// Group 3 (two selected)
						group3: 'filter7,filter8'
					} ),
					msg: 'Given an explicit (complete) filter state object, the result is the same as if the object given represented the model state.'
				},
				{
					// This is mocking case above
					// - 'One filter in one "send_unselected_if_any" group returns the other parameters truthy.'
					input: {
						group1__hidefilter1: 1
					},
					expected: $.extend( true, {}, baseResult, {
						// Group 1 (one selected, the others are true)
						hidefilter2: '1',
						hidefilter3: '1'
					} ),
					msg: 'Given an explicit (incomplete) filter state object, the result is the same as if the object give represented the model state.'
				},
				{
					input: {
						group4__filter10: true
					},
					expected: $.extend( true, {}, baseResult, {
						group4: 'filter10'
					} ),
					msg: 'Given a single value for "single_option" that option is represented in the result.'
				},
				{
					input: {
						group4__filter10: true,
						group4__filter11: true
					},
					expected: $.extend( true, {}, baseResult, {
						group4: 'filter10'
					} ),
					msg: 'Given more than one true value for "single_option" (which should not happen!) only the first value counts, and the second is ignored.'
				},
				{
					input: {},
					expected: baseResult,
					msg: 'Given an explicit empty object, the result is all filters set to their falsey unselected value.'
				}
			];

		model.initializeFilters( definition );
		// Store original state
		const originalState = model.getSelectedState();

		// Test each case
		cases.forEach( ( test ) => {
			assert.deepEqual(
				model.getParametersFromFilters( test.input ),
				test.expected,
				test.msg
			);
		} );

		// After doing the above tests, make sure the actual state
		// of the filter stayed the same
		assert.deepEqual(
			model.getSelectedState(),
			originalState,
			'Running the method with external definition to parse does not actually change the state of the model'
		);
	} );

	QUnit.test( 'getFiltersFromParameters', ( assert ) => {
		let model = new rcfilters.dm.FiltersViewModel();

		model.initializeFilters( filterDefinition, viewsDefinition );

		// Empty query = only default values
		assert.deepEqual(
			model.getFiltersFromParameters( {} ),
			baseFilterRepresentation,
			'Empty parameter query results in an object representing all filters set to their base state'
		);

		assert.deepEqual(
			model.getFiltersFromParameters( {
				filter2: '1'
			} ),
			Object.assign( {}, baseFilterRepresentation, {
				group1__filter1: true, // The text is "show filter 1"
				group1__filter2: false, // The text is "show filter 2"
				group1__filter3: true // The text is "show filter 3"
			} ),
			'One truthy parameter in a group whose other parameters are true by default makes the rest of the filters in the group false (unchecked)'
		);

		assert.deepEqual(
			model.getFiltersFromParameters( {
				filter1: '1',
				filter2: '1',
				filter3: '1'
			} ),
			Object.assign( {}, baseFilterRepresentation, {
				group1__filter1: false, // The text is "show filter 1"
				group1__filter2: false, // The text is "show filter 2"
				group1__filter3: false // The text is "show filter 3"
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
				filter1: '1'
			} )
		);

		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				filter6: '1'
			} )
		);

		// The result here is ignoring the first toggleFiltersSelected call
		assert.deepEqual(
			model.getSelectedState(),
			Object.assign( {}, baseFilterRepresentation, {
				group2__filter4: true,
				group2__filter5: true,
				group2__filter6: false
			} ),
			'getFiltersFromParameters does not care about previous or existing state.'
		);

		// Reset
		model = new rcfilters.dm.FiltersViewModel();
		model.initializeFilters( filterDefinition, viewsDefinition );

		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				group3: 'filter7'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			Object.assign( {}, baseFilterRepresentation, {
				group3__filter7: true,
				group3__filter8: false,
				group3__filter9: false
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
			Object.assign( {}, baseFilterRepresentation, {
				group3__filter7: true,
				group3__filter8: true,
				group3__filter9: false
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
			Object.assign( {}, baseFilterRepresentation, {
				group3__filter7: true,
				group3__filter8: true,
				group3__filter9: true
			} ),
			'A \'string_options\' parameter containing all values, results in all filters of the group as checked.'
		);

		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				group3: 'filter7,all,filter9'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			Object.assign( {}, baseFilterRepresentation, {
				group3__filter7: true,
				group3__filter8: true,
				group3__filter9: true
			} ),
			'A \'string_options\' parameter containing the value \'all\', results in all filters of the group as checked.'
		);

		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				group3: 'filter7,foo,filter9'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			Object.assign( {}, baseFilterRepresentation, {
				group3__filter7: true,
				group3__filter8: false,
				group3__filter9: true
			} ),
			'A \'string_options\' parameter containing an invalid value, results in the invalid value ignored and the valid corresponding filters checked.'
		);

		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				group4: 'option1'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			Object.assign( {}, baseFilterRepresentation, {
				group4__option1: true,
				group4__option2: false
			} ),
			'A \'single_option\' parameter reflects a single selected value.'
		);

		assert.deepEqual(
			model.getFiltersFromParameters( {
				group4: 'option1,option2'
			} ),
			baseFilterRepresentation,
			'An invalid \'single_option\' parameter is ignored.'
		);

		// Change to one value
		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				group4: 'option1'
			} )
		);
		// Change again to another value
		model.toggleFiltersSelected(
			model.getFiltersFromParameters( {
				group4: 'option2'
			} )
		);
		assert.deepEqual(
			model.getSelectedState(),
			Object.assign( {}, baseFilterRepresentation, {
				group4__option2: true
			} ),
			'A \'single_option\' parameter always reflects the latest selected value.'
		);
	} );

	QUnit.test( 'sanitizeStringOptionGroup', ( assert ) => {
		const model = new rcfilters.dm.FiltersViewModel();

		model.initializeFilters( filterDefinition, viewsDefinition );

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

	QUnit.test( 'Filter interaction: subsets', ( assert ) => {
		const model = new rcfilters.dm.FiltersViewModel();

		model.initializeFilters( filterDefinition, viewsDefinition );

		// Select a filter that has subset with another filter
		model.toggleFiltersSelected( {
			group1__filter1: true
		} );

		model.reassessFilterInteractions( model.getItemByName( 'group1__filter1' ) );
		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullFilterState, {
				group1__filter1: { selected: true },
				group1__filter2: { included: true },
				group1__filter3: { included: true },
				// Conflicts are affected
				group2__filter4: { conflicted: true },
				group2__filter5: { conflicted: true },
				group2__filter6: { conflicted: true }
			} ),
			'Filters with subsets are represented in the model.'
		);

		// Select another filter that has a subset with the same previous filter
		model.toggleFiltersSelected( {
			group1__filter2: true
		} );
		model.reassessFilterInteractions( model.getItemByName( 'filter2' ) );
		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullFilterState, {
				group1__filter1: { selected: true },
				group1__filter2: { selected: true, included: true },
				group1__filter3: { included: true },
				// Conflicts are affected
				group2__filter6: { conflicted: true }
			} ),
			'Filters that have multiple subsets are represented.'
		);

		// Remove one filter (but leave the other) that affects filter3
		model.toggleFiltersSelected( {
			group1__filter1: false
		} );
		model.reassessFilterInteractions( model.getItemByName( 'group1__filter1' ) );
		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullFilterState, {
				group1__filter2: { selected: true, included: false },
				group1__filter3: { included: true },
				// Conflicts are affected
				group2__filter6: { conflicted: true }
			} ),
			'Removing a filter only un-includes its subset if there is no other filter affecting.'
		);

		model.toggleFiltersSelected( {
			group1__filter2: false
		} );
		model.reassessFilterInteractions( model.getItemByName( 'group1__filter2' ) );
		assert.deepEqual(
			model.getFullState(),
			baseFullFilterState,
			'Removing all supersets also un-includes the subsets.'
		);
	} );

	QUnit.test( 'Filter interaction: full coverage', ( assert ) => {
		const model = new rcfilters.dm.FiltersViewModel(),
			isCapsuleItemMuted = function ( filterName ) {
				const itemModel = model.getItemByName( filterName ),
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
					group1__filter1: isCapsuleItemMuted( 'group1__filter1' ),
					group1__filter2: isCapsuleItemMuted( 'group1__filter2' ),
					group1__filter3: isCapsuleItemMuted( 'group1__filter3' ),
					group2__filter4: isCapsuleItemMuted( 'group2__filter4' ),
					group2__filter5: isCapsuleItemMuted( 'group2__filter5' ),
					group2__filter6: isCapsuleItemMuted( 'group2__filter6' )
				};
			},
			baseMuteState = {
				group1__filter1: false,
				group1__filter2: false,
				group1__filter3: false,
				group2__filter4: false,
				group2__filter5: false,
				group2__filter6: false
			};

		model.initializeFilters( filterDefinition, viewsDefinition );

		// Starting state, no selection, all items are non-muted
		assert.deepEqual(
			getCurrentItemsMutedState(),
			baseMuteState,
			'No selection - all items are non-muted'
		);

		// Select most (but not all) items in each group
		model.toggleFiltersSelected( {
			group1__filter1: true,
			group1__filter2: true,
			group2__filter4: true,
			group2__filter5: true
		} );

		// Both groups have multiple (but not all) items selected, all items are non-muted
		assert.deepEqual(
			getCurrentItemsMutedState(),
			baseMuteState,
			'Not all items in the group selected - all items are non-muted'
		);

		// Select all items in 'fullCoverage' group (group2)
		model.toggleFiltersSelected( {
			group2__filter6: true
		} );

		// Group2 (full coverage) has all items selected, all its items are muted
		assert.deepEqual(
			getCurrentItemsMutedState(),
			Object.assign( {}, baseMuteState, {
				group2__filter4: true,
				group2__filter5: true,
				group2__filter6: true
			} ),
			'All items in \'full coverage\' group are selected - all items in the group are muted'
		);

		// Select all items in non 'fullCoverage' group (group1)
		model.toggleFiltersSelected( {
			group1__filter3: true
		} );

		// Group1 (full coverage) has all items selected, no items in it are muted (non full coverage)
		assert.deepEqual(
			getCurrentItemsMutedState(),
			Object.assign( {}, baseMuteState, {
				group2__filter4: true,
				group2__filter5: true,
				group2__filter6: true
			} ),
			'All items in a non \'full coverage\' group are selected - none of the items in the group are muted'
		);

		// Uncheck an item from each group
		model.toggleFiltersSelected( {
			group1__filter3: false,
			group2__filter5: false
		} );
		assert.deepEqual(
			getCurrentItemsMutedState(),
			baseMuteState,
			'Not all items in the group are checked - all items are non-muted regardless of group coverage'
		);
	} );

	QUnit.test( 'Filter interaction: conflicts', ( assert ) => {
		let model = new rcfilters.dm.FiltersViewModel();

		model.initializeFilters( filterDefinition, viewsDefinition );

		assert.deepEqual(
			model.getFullState(),
			baseFullFilterState,
			'Initial state: no conflicts because no selections.'
		);

		// Select a filter that has a conflict with an entire group
		model.toggleFiltersSelected( {
			group1__filter1: true // conflicts: entire of group 2 ( filter4, filter5, filter6)
		} );

		model.reassessFilterInteractions( model.getItemByName( 'group1__filter1' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullFilterState, {
				group1__filter1: { selected: true },
				group2__filter4: { conflicted: true },
				group2__filter5: { conflicted: true },
				group2__filter6: { conflicted: true },
				// Subsets are affected by the selection
				group1__filter2: { included: true },
				group1__filter3: { included: true }
			} ),
			'Selecting a filter that conflicts with a group sets all the conflicted group items as "conflicted".'
		);

		// Select one of the conflicts (both filters are now conflicted and selected)
		model.toggleFiltersSelected( {
			group2__filter4: true // conflicts: filter 1
		} );
		model.reassessFilterInteractions( model.getItemByName( 'group2__filter4' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullFilterState, {
				group1__filter1: { selected: true, conflicted: true },
				group2__filter4: { selected: true, conflicted: true },
				group2__filter5: { conflicted: true },
				group2__filter6: { conflicted: true },
				// Subsets are affected by the selection
				group1__filter2: { included: true },
				group1__filter3: { included: true }
			} ),
			'Selecting a conflicting filter inside a group, sets both sides to conflicted and selected.'
		);

		// Reset
		model = new rcfilters.dm.FiltersViewModel();
		model.initializeFilters( filterDefinition, viewsDefinition );

		// Select a filter that has a conflict with a specific filter
		model.toggleFiltersSelected( {
			group1__filter2: true // conflicts: filter6
		} );
		model.reassessFilterInteractions( model.getItemByName( 'group1__filter2' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullFilterState, {
				group1__filter2: { selected: true },
				group2__filter6: { conflicted: true },
				// Subsets are affected by the selection
				group1__filter3: { included: true }
			} ),
			'Selecting a filter that conflicts with another filter sets the other as "conflicted".'
		);

		// Select the conflicting filter
		model.toggleFiltersSelected( {
			group2__filter6: true // conflicts: filter2
		} );

		model.reassessFilterInteractions( model.getItemByName( 'group2__filter6' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullFilterState, {
				group1__filter2: { selected: true, conflicted: true },
				group2__filter6: { selected: true, conflicted: true },
				// This is added to the conflicts because filter6 is part of group2,
				// who is in conflict with filter1; note that filter2 also conflicts
				// with filter6 which means that filter1 conflicts with filter6 (because it's in group2)
				// and also because its **own sibling** (filter2) is **also** in conflict with the
				// selected items in group2 (filter6)
				group1__filter1: { conflicted: true },

				// Subsets are affected by the selection
				group1__filter3: { included: true }
			} ),
			'Selecting a conflicting filter with an individual filter, sets both sides to conflicted and selected.'
		);

		// Now choose a non-conflicting filter from the group
		model.toggleFiltersSelected( {
			group2__filter5: true
		} );

		model.reassessFilterInteractions( model.getItemByName( 'group2__filter5' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullFilterState, {
				group1__filter2: { selected: true },
				group2__filter6: { selected: true },
				group2__filter5: { selected: true },
				// Filter6 and filter1 are no longer in conflict because
				// filter5, while it is in conflict with filter1, it is
				// not in conflict with filter2 - and since filter2 is
				// selected, it removes the conflict bidirectionally

				// Subsets are affected by the selection
				group1__filter3: { included: true }
			} ),
			'Selecting a non-conflicting filter within the group of a conflicting filter removes the conflicts.'
		);

		// Followup on the previous test, unselect filter2 so filter1
		// is now the only one selected in its own group, and since
		// it is in conflict with the entire of group2, it means
		// filter1 is once again conflicted
		model.toggleFiltersSelected( {
			group1__filter2: false
		} );

		model.reassessFilterInteractions( model.getItemByName( 'group1__filter2' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullFilterState, {
				group1__filter1: { conflicted: true },
				group2__filter6: { selected: true },
				group2__filter5: { selected: true }
			} ),
			'Unselecting an item that did not conflict returns the conflict state.'
		);

		// Followup #2: Now actually select filter1, and make everything conflicted
		model.toggleFiltersSelected( {
			group1__filter1: true
		} );

		model.reassessFilterInteractions( model.getItemByName( 'group1__filter1' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullFilterState, {
				group1__filter1: { selected: true, conflicted: true },
				group2__filter6: { selected: true, conflicted: true },
				group2__filter5: { selected: true, conflicted: true },
				group2__filter4: { conflicted: true }, // Not selected but conflicted because it's in group2
				// Subsets are affected by the selection
				group1__filter2: { included: true },
				group1__filter3: { included: true }
			} ),
			'Selecting an item that conflicts with a whole group makes all selections in that group conflicted.'
		);

		/* Simple case */
		// Reset
		model = new rcfilters.dm.FiltersViewModel();
		model.initializeFilters( filterDefinition, viewsDefinition );

		// Select a filter that has a conflict with a specific filter
		model.toggleFiltersSelected( {
			group1__filter2: true // conflicts: filter6
		} );

		model.reassessFilterInteractions( model.getItemByName( 'group1__filter2' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullFilterState, {
				group1__filter2: { selected: true },
				group2__filter6: { conflicted: true },
				// Subsets are affected by the selection
				group1__filter3: { included: true }
			} ),
			'Simple case: Selecting a filter that conflicts with another filter sets the other as "conflicted".'
		);

		model.toggleFiltersSelected( {
			group1__filter3: true // conflicts: filter6
		} );

		model.reassessFilterInteractions( model.getItemByName( 'group1__filter3' ) );

		assert.deepEqual(
			model.getFullState(),
			$.extend( true, {}, baseFullFilterState, {
				group1__filter2: { selected: true },
				// Subsets are affected by the selection
				group1__filter3: { selected: true, included: true }
			} ),
			'Simple case: Selecting a filter that is not in conflict removes the conflict.'
		);
	} );

	QUnit.test( 'Filter highlights', ( assert ) => {
		// We are using a different (smaller) definition here than the global one
		const definition = [ {
			name: 'group1',
			title: 'Group 1',
			type: 'string_options',
			filters: [
				{ name: 'filter1', cssClass: 'class1', label: '1', description: '1' },
				{ name: 'filter2', cssClass: 'class2', label: '2', description: '2' },
				{ name: 'filter3', cssClass: 'class3', label: '3', description: '3' },
				{ name: 'filter4', cssClass: 'class4', label: '4', description: '4' },
				{ name: 'filter5', cssClass: 'class5', label: '5', description: '5' },
				{ name: 'filter6', label: '6', description: '6' }
			]
		} ];
		let model = new rcfilters.dm.FiltersViewModel();

		model.initializeFilters( definition );

		assert.false(
			model.isHighlightEnabled(),
			'Initially, highlight is disabled.'
		);

		model.toggleHighlight( true );
		assert.true(
			model.isHighlightEnabled(),
			'Highlight is enabled on toggle.'
		);

		model.setHighlightColor( 'group1__filter1', 'color1' );
		model.setHighlightColor( 'group1__filter2', 'color2' );

		assert.deepEqual(
			model.getHighlightedItems().map( ( item ) => item.getName() ),
			[
				'group1__filter1',
				'group1__filter2'
			],
			'Highlighted items are highlighted.'
		);

		assert.strictEqual(
			model.getItemByName( 'group1__filter1' ).getHighlightColor(),
			'color1',
			'Item highlight color is set.'
		);

		model.setHighlightColor( 'group1__filter1', 'color1changed' );
		assert.strictEqual(
			model.getItemByName( 'group1__filter1' ).getHighlightColor(),
			'color1changed',
			'Item highlight color is changed on setHighlightColor.'
		);

		model.clearHighlightColor( 'group1__filter1' );
		assert.deepEqual(
			model.getHighlightedItems().map( ( item ) => item.getName() ),
			[
				'group1__filter2'
			],
			'Clear highlight from an item results in the item no longer being highlighted.'
		);

		// Reset
		model = new rcfilters.dm.FiltersViewModel();
		model.initializeFilters( definition );

		model.setHighlightColor( 'group1__filter1', 'color1' );
		model.setHighlightColor( 'group1__filter2', 'color2' );
		model.setHighlightColor( 'group1__filter3', 'color3' );

		assert.deepEqual(
			model.getHighlightedItems().map( ( item ) => item.getName() ),
			[
				'group1__filter1',
				'group1__filter2',
				'group1__filter3'
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
		model = new rcfilters.dm.FiltersViewModel();
		model.initializeFilters( definition );

		model.setHighlightColor( 'group1__filter1', 'color1' );
		model.setHighlightColor( 'group1__filter6', 'color6' );

		assert.deepEqual(
			model.getHighlightedItems().map( ( item ) => item.getName() ),
			[
				'group1__filter1'
			],
			'Items without a specified class identifier are not highlighted.'
		);
	} );

	QUnit.test( 'emptyAllFilters', ( assert ) => {
		const model = new rcfilters.dm.FiltersViewModel();

		model.initializeFilters( shortFilterDefinition, null );

		model.toggleFiltersSelected( {
			group1__filter1: true,
			group2__filter4: true, // hidden
			group3__filter5: true // sticky
		} );

		model.emptyAllFilters();

		assert.deepEqual(
			model.getSelectedState( true ),
			{
				group3__filter5: true,
				group3__filter6: true
			},
			'Emptying filters does not affect sticky filters'
		);
	} );

	QUnit.test( 'areVisibleFiltersEmpty', ( assert ) => {
		const model = new rcfilters.dm.FiltersViewModel();
		model.initializeFilters( shortFilterDefinition, null );

		model.emptyAllFilters();
		assert.true( model.areVisibleFiltersEmpty() );

		model.toggleFiltersSelected( {
			group3__filter5: true // sticky
		} );
		assert.true( model.areVisibleFiltersEmpty() );

		model.toggleFiltersSelected( {
			group1__filter1: true
		} );
		assert.false( model.areVisibleFiltersEmpty() );
	} );
}() );
