/* eslint-disable camelcase */
/* eslint no-underscore-dangle: "off" */
( function ( mw, $ ) {
	var mockFilterStructure = [ {
			name: 'group1',
			title: 'Group 1',
			type: 'send_unselected_if_any',
			filters: [
				{ name: 'filter1', default: true },
				{ name: 'filter2' }
			]
		}, {
			name: 'group2',
			title: 'Group 2',
			type: 'send_unselected_if_any',
			filters: [
				{ name: 'filter3' },
				{ name: 'filter4', default: true }
			]
		}, {
			name: 'group3',
			title: 'Group 3',
			type: 'string_options',
			filters: [
				{ name: 'filter5' },
				{ name: 'filter6' }
			]
		} ],
		minimalDefaultParams = {
			filter1: '1',
			filter4: '1'
		};

	QUnit.module( 'mediawiki.rcfilters - UriProcessor' );

	QUnit.test( 'getVersion', function ( assert ) {
		var uriProcessor = new mw.rcfilters.UriProcessor( new mw.rcfilters.dm.FiltersViewModel() );

		assert.ok(
			uriProcessor.getVersion( { param1: 'foo', urlversion: '2' } ),
			2,
			'Retrieving the version from the URI query'
		);

		assert.ok(
			uriProcessor.getVersion( { param1: 'foo' } ),
			1,
			'Getting version 1 if no version is specified'
		);
	} );

	QUnit.test( 'updateModelBasedOnQuery & getUriParametersFromModel', function ( assert ) {
		var uriProcessor,
			filtersModel = new mw.rcfilters.dm.FiltersViewModel(),
			baseParams = {
				filter1: '0',
				filter2: '0',
				filter3: '0',
				filter4: '0',
				group3: '',
				highlight: '0',
				invert: '0',
				group1__filter1_color: null,
				group1__filter2_color: null,
				group2__filter3_color: null,
				group2__filter4_color: null,
				group3__filter5_color: null,
				group3__filter6_color: null
			};

		filtersModel.initializeFilters( mockFilterStructure );
		uriProcessor = new mw.rcfilters.UriProcessor( filtersModel );

		uriProcessor.updateModelBasedOnQuery( {} );
		assert.deepEqual(
			uriProcessor.getUriParametersFromModel(),
			$.extend( true, {}, baseParams, minimalDefaultParams ),
			'Version 1: Empty url query sets model to defaults'
		);

		uriProcessor.updateModelBasedOnQuery( { urlversion: '2' } );
		assert.deepEqual(
			uriProcessor.getUriParametersFromModel(),
			baseParams,
			'Version 2: Empty url query sets model to all-false'
		);

		uriProcessor.updateModelBasedOnQuery( { filter1: '1', urlversion: '2' } );
		assert.deepEqual(
			uriProcessor.getUriParametersFromModel(),
			$.extend( true, {}, baseParams, { filter1: '1' } ),
			'Parameters in Uri query set parameter value in the model'
		);

		uriProcessor.updateModelBasedOnQuery( { highlight: '1', group1__filter1_color: 'c1', urlversion: '2' } );
		assert.deepEqual(
			uriProcessor.getUriParametersFromModel(),
			$.extend( true, {}, baseParams, {
				highlight: '1',
				group1__filter1_color: 'c1'
			} ),
			'Highlight parameters in Uri query set highlight state in the model'
		);

		uriProcessor.updateModelBasedOnQuery( { invert: '1', urlversion: '2' } );
		assert.deepEqual(
			uriProcessor.getUriParametersFromModel(),
			$.extend( true, {}, baseParams, {
				invert: '1'
			} ),
			'Invert parameter in Uri query set invert state in the model'
		);
	} );

	QUnit.test( 'isNewState', function ( assert ) {
		var uriProcessor,
			filtersModel = new mw.rcfilters.dm.FiltersViewModel(),
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
		uriProcessor = new mw.rcfilters.UriProcessor( filtersModel );

		cases.forEach( function ( testCase ) {
			assert.equal(
				uriProcessor.isNewState( testCase.states.curr, testCase.states.new ),
				testCase.result,
				testCase.message
			);
		} );
	} );

	QUnit.test( 'doesQueryContainRecognizedParams', function ( assert ) {
		var uriProcessor,
			filtersModel = new mw.rcfilters.dm.FiltersViewModel(),
			cases = [
				{
					query: {},
					result: false,
					message: 'Empty query is not valid for load.'
				},
				{
					query: { highlight: '1' },
					result: false,
					message: 'Highlight state alone is not valid for load'
				},
				{
					query: { urlversion: '2' },
					result: true,
					message: 'urlversion=2 state alone is valid for load as an empty state'
				},
				{
					query: { filter1: '1', foo: 'bar' },
					result: true,
					message: 'Existence of recognized parameters makes the query valid for load'
				},
				{
					query: { foo: 'bar', debug: true },
					result: false,
					message: 'Only unrecognized parameters makes the query invalid for load'
				}
			];

		filtersModel.initializeFilters( mockFilterStructure );
		uriProcessor = new mw.rcfilters.UriProcessor( filtersModel );

		cases.forEach( function ( testCase ) {
			assert.equal(
				uriProcessor.doesQueryContainRecognizedParams( testCase.query ),
				testCase.result,
				testCase.message
			);
		} );
	} );

	QUnit.test( '_getNormalizedQueryParams', function ( assert ) {
		var uriProcessor,
			filtersModel = new mw.rcfilters.dm.FiltersViewModel(),
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
		uriProcessor = new mw.rcfilters.UriProcessor( filtersModel );

		cases.forEach( function ( testCase ) {
			assert.deepEqual(
				uriProcessor._getNormalizedQueryParams( testCase.query ),
				testCase.result,
				testCase.message
			);
		} );
	} );

}( mediaWiki, jQuery ) );
