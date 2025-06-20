/* eslint-disable camelcase */
( function () {
	QUnit.module( 'mediawiki.rcfilters - FilterItem' );
	const rcfilters = require( 'mediawiki.rcfilters.filters.ui' );

	QUnit.test( 'Initializing filter item', ( assert ) => {
		const group1 = new rcfilters.dm.FilterGroup( 'group1' ),
			group2 = new rcfilters.dm.FilterGroup( 'group2' );

		let item = new rcfilters.dm.FilterItem( 'filter1', group1 );
		assert.strictEqual(
			item.getName(),
			'group1__filter1',
			'Filter name is retained.'
		);
		assert.strictEqual(
			item.getGroupName(),
			'group1',
			'Group name is retained.'
		);

		item = new rcfilters.dm.FilterItem(
			'filter1',
			group1,
			{
				label: 'test label',
				description: 'test description'
			}
		);
		assert.strictEqual(
			item.getLabel(),
			'test label',
			'Label information is retained.'
		);
		assert.strictEqual(
			item.getLabel(),
			'test label',
			'Description information is retained.'
		);

		item = new rcfilters.dm.FilterItem(
			'filter1',
			group1,
			{
				selected: true
			}
		);
		assert.true(
			item.isSelected(),
			'Item can be selected in the config.'
		);
		item.toggleSelected( true );
		assert.true(
			item.isSelected(),
			'Item can toggle its selected state.'
		);

		// Subsets
		item = new rcfilters.dm.FilterItem(
			'filter1',
			group1,
			{
				subset: [ 'sub1', 'sub2', 'sub3' ]
			}
		);
		assert.deepEqual(
			item.getSubset(),
			[ 'sub1', 'sub2', 'sub3' ],
			'Subset information is retained.'
		);
		assert.true(
			item.existsInSubset( 'sub1' ),
			'Specific item exists in subset.'
		);
		assert.false(
			item.existsInSubset( 'sub10' ),
			'Specific item does not exists in subset.'
		);
		assert.false(
			item.isIncluded(),
			'Initial state of "included" is false.'
		);

		item.toggleIncluded( true );
		assert.true(
			item.isIncluded(),
			'Item toggles its included state.'
		);

		// Conflicts
		item = new rcfilters.dm.FilterItem(
			'filter1',
			group1,
			{
				conflicts: {
					group2__conflict1: { group: 'group2', filter: 'group2__conflict1' },
					group2__conflict2: { group: 'group2', filter: 'group2__conflict2' },
					group2__conflict3: { group: 'group2', filter: 'group2__conflict3' }
				}
			}
		);
		assert.deepEqual(
			item.getConflicts(),
			{
				group2__conflict1: { group: 'group2', filter: 'group2__conflict1' },
				group2__conflict2: { group: 'group2', filter: 'group2__conflict2' },
				group2__conflict3: { group: 'group2', filter: 'group2__conflict3' }
			},
			'Conflict information is retained.'
		);
		assert.strictEqual(
			item.existsInConflicts( new rcfilters.dm.FilterItem( 'conflict1', group2 ) ),
			true,
			'Specific item exists in conflicts.'
		);
		assert.strictEqual(
			item.existsInConflicts( new rcfilters.dm.FilterItem( 'conflict10', group1 ) ),
			false,
			'Specific item does not exists in conflicts.'
		);
		assert.false(
			item.isConflicted(),
			'Initial state of "conflicted" is false.'
		);

		item.toggleConflicted( true );
		assert.true(
			item.isConflicted(),
			'Item toggles its conflicted state.'
		);

		// Fully covered
		item = new rcfilters.dm.FilterItem( 'filter1', group1 );
		assert.false(
			item.isFullyCovered(),
			'Initial state of "full coverage" is false.'
		);
		item.toggleFullyCovered( true );
		assert.true(
			item.isFullyCovered(),
			'Item toggles its fully coverage state.'
		);

	} );

	QUnit.test( 'Emitting events', ( assert ) => {
		const group1 = new rcfilters.dm.FilterGroup( 'group1' ),
			item = new rcfilters.dm.FilterItem( 'filter1', group1 ),
			events = [];

		// Listen to update events
		item.on( 'update', () => {
			events.push( item.getState() );
		} );

		// Do stuff
		item.toggleSelected( true ); // { selected: true, included: false, conflicted: false, fullyCovered: false }
		item.toggleSelected( true ); // No event (duplicate state)
		item.toggleIncluded( true ); // { selected: true, included: true, conflicted: false, fullyCovered: false }
		item.toggleConflicted( true ); // { selected: true, included: true, conflicted: true, fullyCovered: false }
		item.toggleFullyCovered( true ); // { selected: true, included: true, conflicted: true, fullyCovered: true }
		item.toggleSelected(); // { selected: false, included: true, conflicted: true, fullyCovered: true }

		// Check emitted events
		assert.deepEqual(
			events,
			[
				{ selected: true, included: false, conflicted: false, fullyCovered: false },
				{ selected: true, included: true, conflicted: false, fullyCovered: false },
				{ selected: true, included: true, conflicted: true, fullyCovered: false },
				{ selected: true, included: true, conflicted: true, fullyCovered: true },
				{ selected: false, included: true, conflicted: true, fullyCovered: true }
			],
			'Events emitted successfully.'
		);
	} );

	QUnit.test( 'get/set boolean value', ( assert ) => {
		const group = new rcfilters.dm.FilterGroup( 'group1', { type: 'boolean' } ),
			item = new rcfilters.dm.FilterItem( 'filter1', group );

		item.setValue( '1' );

		assert.true( item.getValue(), 'Value is coerced to boolean' );
	} );

	QUnit.test( 'get/set any value', ( assert ) => {
		const group = new rcfilters.dm.FilterGroup( 'group1', { type: 'any_value' } ),
			item = new rcfilters.dm.FilterItem( 'filter1', group );

		item.setValue( '1' );

		assert.strictEqual( item.getValue(), '1', 'Value is kept as-is' );
	} );
}() );
