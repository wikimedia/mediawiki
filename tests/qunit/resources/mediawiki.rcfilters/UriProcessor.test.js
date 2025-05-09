/* eslint-disable camelcase */
/* eslint no-underscore-dangle: "off" */
( function () {
	const rcfilters = require( 'mediawiki.rcfilters.filters.ui' );
	const mockFilterStructure = [ {
			name: 'group1',
			title: 'Group 1',
			type: 'send_unselected_if_any',
			filters: [
				{ name: 'filter1', cssClass: 'filter1class', default: true },
				{ name: 'filter2', cssClass: 'filter2class' }
			]
		}, {
			name: 'group2',
			title: 'Group 2',
			type: 'send_unselected_if_any',
			filters: [
				{ name: 'filter3', cssClass: 'filter3class' },
				{ name: 'filter4', cssClass: 'filter4class', default: true }
			]
		}, {
			name: 'group3',
			title: 'Group 3',
			type: 'string_options',
			filters: [
				{ name: 'filter5', cssClass: 'filter5class' },
				{ name: 'filter6' } // Not supporting highlights
			]
		}, {
			name: 'group4',
			title: 'Group 4',
			type: 'boolean',
			sticky: true,
			filters: [
				{ name: 'stickyFilter7', cssClass: 'filter7class' },
				{ name: 'stickyFilter8', cssClass: 'filter8class' }
			]
		} ],
		minimalDefaultParams = {
			filter1: '1',
			filter4: '1'
		};

	QUnit.module( 'mediawiki.rcfilters - UriProcessor' );

	QUnit.test( 'getVersion', ( assert ) => {
		const uriProcessor = new rcfilters.UriProcessor( new rcfilters.dm.FiltersViewModel() );

		assert.strictEqual(
			uriProcessor.getVersion( new URLSearchParams( { param1: 'foo', urlversion: '2' } ) ),
			2,
			'Retrieving the version from the URI query'
		);

		assert.strictEqual(
			uriProcessor.getVersion( new URLSearchParams( { param1: 'foo' } ) ),
			1,
			'Getting version 1 if no version is specified'
		);
	} );

	QUnit.test( 'getUpdatedUri', ( assert ) => {
		const filtersModel = new rcfilters.dm.FiltersViewModel(),
			makeUri = function ( queryParams ) {
				const uri = new URL( 'http://server/wiki/Special:RC' );
				uri.search = new URLSearchParams( queryParams ).toString();
				return uri;
			};

		filtersModel.initializeFilters( mockFilterStructure );
		const uriProcessor = new rcfilters.UriProcessor( filtersModel );

		assert.deepEqual(
			( uriProcessor.getUpdatedUri( makeUri( {} ) ) ).searchParams,
			new URLSearchParams( { urlversion: '2' } ),
			'Empty model state with empty uri state, assumes the given uri is already normalized, and adds urlversion=2'
		);

		assert.deepEqual(
			( uriProcessor.getUpdatedUri( makeUri( { foo: 'bar' } ) ) ).searchParams,
			new URLSearchParams( { urlversion: '2', foo: 'bar' } ),
			'Empty model state with unrecognized params retains unrecognized params'
		);

		// Update the model
		filtersModel.toggleFiltersSelected( {
			group1__filter1: true, // Param: filter2: '1'
			group3__filter5: true // Param: group3: 'filter5'
		} );

		assert.deepEqual(
			( uriProcessor.getUpdatedUri( makeUri( {} ) ) ).searchParams,
			new URLSearchParams( { urlversion: '2', filter2: '1', group3: 'filter5' } ),
			'Model state is reflected in the updated URI'
		);

		assert.deepEqual(
			( uriProcessor.getUpdatedUri( makeUri( { foo: 'bar' } ) ) ).searchParams,
			new URLSearchParams( { urlversion: '2', filter2: '1', group3: 'filter5', foo: 'bar' } ),
			'Model state is reflected in the updated URI with existing uri params'
		);
	} );

	QUnit.test( 'updateModelBasedOnQuery', ( assert ) => {
		const filtersModel = new rcfilters.dm.FiltersViewModel();

		filtersModel.initializeFilters( mockFilterStructure );
		const uriProcessor = new rcfilters.UriProcessor( filtersModel );

		uriProcessor.updateModelBasedOnQuery( new URLSearchParams( {} ) );
		assert.deepEqual(
			filtersModel.getCurrentParameterState(),
			minimalDefaultParams,
			'Version 1: Empty url query sets model to defaults'
		);

		uriProcessor.updateModelBasedOnQuery( new URLSearchParams( { urlversion: '2' } ) );
		assert.deepEqual(
			filtersModel.getCurrentParameterState(),
			{},
			'Version 2: Empty url query sets model to all-false'
		);

		uriProcessor.updateModelBasedOnQuery( new URLSearchParams( { filter1: '1', urlversion: '2' } ) );
		assert.deepEqual(
			filtersModel.getCurrentParameterState(),
			$.extend( true, {}, { filter1: '1' } ),
			'Parameters in Uri query set parameter value in the model'
		);
	} );

	QUnit.test( 'isNewState', ( assert ) => {
		const filtersModel = new rcfilters.dm.FiltersViewModel(),
			cases = [
				{
					states: {
						curr: {},
						new: {}
					},
					result: false,
					message: 'Empty objects are not new state.'
				},
				{
					states: {
						curr: { filter1: '1' },
						new: { filter1: '0' }
					},
					result: true,
					message: 'Nulified parameter is a new state'
				},
				{
					states: {
						curr: { filter1: '1' },
						new: { filter1: '1', filter2: '1' }
					},
					result: true,
					message: 'Added parameters are a new state'
				},
				{
					states: {
						curr: { filter1: '1' },
						new: { filter1: '1', filter2: '0' }
					},
					result: false,
					message: 'Added null parameters are not a new state (normalizing equals old state)'
				},
				{
					states: {
						curr: { filter1: '1' },
						new: { filter1: '1', foo: 'bar' }
					},
					result: true,
					message: 'Added unrecognized parameters are a new state'
				},
				{
					states: {
						curr: { filter1: '1', foo: 'bar' },
						new: { filter1: '1', foo: 'baz' }
					},
					result: true,
					message: 'Changed unrecognized parameters are a new state'
				}
			];

		filtersModel.initializeFilters( mockFilterStructure );
		const uriProcessor = new rcfilters.UriProcessor( filtersModel );

		cases.forEach( ( testCase ) => {
			assert.strictEqual(
				uriProcessor.isNewState(
					new URLSearchParams( testCase.states.curr ),
					new URLSearchParams( testCase.states.new )
				),
				testCase.result,
				testCase.message
			);
		} );
	} );

	QUnit.test( '_getNormalizedQueryParams', ( assert ) => {
		const filtersModel = new rcfilters.dm.FiltersViewModel(),
			cases = [
				{
					query: {},
					result: $.extend( true, { urlversion: '2' }, minimalDefaultParams ),
					message: 'Empty query returns defaults (urlversion 1).'
				},
				{
					query: { urlversion: '2' },
					result: { urlversion: '2' },
					message: 'Empty query returns empty (urlversion 2)'
				},
				{
					query: { filter1: '0' },
					result: { urlversion: '2', filter4: '1' },
					message: 'urlversion 1 returns query that overrides defaults'
				},
				{
					query: { filter3: '1' },
					result: { urlversion: '2', filter1: '1', filter4: '1', filter3: '1' },
					message: 'urlversion 1 with an extra param value returns query that is joined with defaults'
				}
			];

		filtersModel.initializeFilters( mockFilterStructure );
		const uriProcessor = new rcfilters.UriProcessor( filtersModel );

		cases.forEach( ( testCase ) => {
			assert.deepEqual(
				uriProcessor._getNormalizedQueryParams( new URLSearchParams( testCase.query ) ),
				new URLSearchParams( testCase.result ),
				testCase.message
			);
		} );
	} );

	QUnit.test( '_normalizeTargetInUri', ( assert ) => {
		const cases = [
			{
				input: 'http://host/wiki/Special:RecentChangesLinked/Moai',
				output: 'http://host/wiki/Special:RecentChangesLinked?target=Moai',
				message: 'Target as subpage in path'
			},
			{
				input: 'http://host/wiki/Special:RecentChangesLinked/Château',
				output: 'http://host/wiki/Special:RecentChangesLinked?target=Château',
				message: 'Target as subpage in path with special characters'
			},
			{
				input: 'http://host/wiki/Special:RecentChangesLinked/Moai/Sub1',
				output: 'http://host/wiki/Special:RecentChangesLinked?target=Moai/Sub1',
				message: 'Target as subpage also has a subpage'
			},
			{
				input: 'http://host/wiki/Special:RecentChangesLinked/Category:Foo',
				output: 'http://host/wiki/Special:RecentChangesLinked?target=Category:Foo',
				message: 'Target as subpage in path (with namespace)'
			},
			{
				input: 'http://host/wiki/Special:RecentChangesLinked/Category:Foo/Bar',
				output: 'http://host/wiki/Special:RecentChangesLinked?target=Category:Foo/Bar',
				message: 'Target as subpage in path also has a subpage (with namespace)'
			},
			{
				input: 'http://host/w/index.php?title=Special:RecentChangesLinked/Moai',
				output: 'http://host/w/index.php?title=Special:RecentChangesLinked&target=Moai',
				message: 'Target as subpage in title param'
			},
			{
				input: 'http://host/w/index.php?title=Special:RecentChangesLinked/Moai/Sub1',
				output: 'http://host/w/index.php?title=Special:RecentChangesLinked&target=Moai/Sub1',
				message: 'Target as subpage in title param also has a subpage'
			},
			{
				input: 'http://host/w/index.php?title=Special:RecentChangesLinked/Category:Foo/Bar',
				output: 'http://host/w/index.php?title=Special:RecentChangesLinked&target=Category:Foo/Bar',
				message: 'Target as subpage in title param also has a subpage (with namespace)'
			},
			{
				input: 'http://host/wiki/Special:Watchlist',
				output: 'http://host/wiki/Special:Watchlist',
				message: 'No target specified'
			},
			{
				normalizeTarget: false,
				input: 'http://host/wiki/Special:RecentChanges/Foo',
				output: 'http://host/wiki/Special:RecentChanges/Foo',
				message: 'Do not normalize if "normalizeTarget" is false.'
			}
		];

		cases.forEach( ( testCase ) => {
			const uriProcessor = new rcfilters.UriProcessor(
				null,
				{
					normalizeTarget: testCase.normalizeTarget === undefined ?
						true : testCase.normalizeTarget
				}
			);

			assert.strictEqual(
				uriProcessor._normalizeTargetInUri( new URL( testCase.input ) ).searchParams.get( 'target' ),
				new URL( testCase.output ).searchParams.get( 'target' ),
				testCase.message
			);
		} );
	} );

}() );
