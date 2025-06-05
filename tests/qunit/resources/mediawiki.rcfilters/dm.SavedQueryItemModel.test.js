/* eslint-disable camelcase */
( function () {
	const rcfilters = require( 'mediawiki.rcfilters.filters.ui' );
	const itemData = {
		params: {
			param1: '1',
			param2: 'foo|bar',
			invert: '0'
		},
		highlights: {
			param1_color: 'c1',
			param2_color: 'c2'
		}
	};

	QUnit.module( 'mediawiki.rcfilters - SavedQueryItemModel' );

	QUnit.test( 'Initializing and getters', ( assert ) => {
		const model = new rcfilters.dm.SavedQueryItemModel(
			'randomID',
			'Some label',
			$.extend( true, {}, itemData )
		);

		assert.strictEqual(
			model.getID(),
			'randomID',
			'Item ID is retained'
		);

		assert.strictEqual(
			model.getLabel(),
			'Some label',
			'Item label is retained'
		);

		assert.deepEqual(
			model.getData(),
			itemData,
			'Item data is retained'
		);

		assert.false(
			model.isDefault(),
			'Item default state is retained.'
		);
	} );

	QUnit.test( 'Default', ( assert ) => {
		let model;

		model = new rcfilters.dm.SavedQueryItemModel(
			'randomID',
			'Some label',
			$.extend( true, {}, itemData )
		);

		assert.false(
			model.isDefault(),
			'Default state represented when item initialized with default:false.'
		);

		model.toggleDefault( true );
		assert.true(
			model.isDefault(),
			'Default state toggles to true successfully'
		);

		model.toggleDefault( false );
		assert.false(
			model.isDefault(),
			'Default state toggles to false successfully'
		);

		// Reset
		model = new rcfilters.dm.SavedQueryItemModel(
			'randomID',
			'Some label',
			$.extend( true, {}, itemData ),
			{ default: true }
		);

		assert.true(
			model.isDefault(),
			'Default state represented when item initialized with default:true.'
		);
	} );
}() );
