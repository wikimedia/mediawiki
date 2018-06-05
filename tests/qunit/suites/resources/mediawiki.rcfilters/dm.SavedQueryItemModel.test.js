/* eslint-disable camelcase */
( function ( mw ) {
	var itemData = {
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

	QUnit.test( 'Initializing and getters', function ( assert ) {
		var model;

		model = new mw.rcfilters.dm.SavedQueryItemModel(
			'randomID',
			'Some label',
			$.extend( true, {}, itemData )
		);

		assert.equal(
			model.getID(),
			'randomID',
			'Item ID is retained'
		);

		assert.equal(
			model.getLabel(),
			'Some label',
			'Item label is retained'
		);

		assert.deepEqual(
			model.getData(),
			itemData,
			'Item data is retained'
		);

		assert.equal(
			model.isDefault(),
			false,
			'Item default state is retained.'
		);
	} );

	QUnit.test( 'Default', function ( assert ) {
		var model;

		model = new mw.rcfilters.dm.SavedQueryItemModel(
			'randomID',
			'Some label',
			$.extend( true, {}, itemData )
		);

		assert.equal(
			model.isDefault(),
			false,
			'Default state represented when item initialized with default:false.'
		);

		model.toggleDefault( true );
		assert.equal(
			model.isDefault(),
			true,
			'Default state toggles to true successfully'
		);

		model.toggleDefault( false );
		assert.equal(
			model.isDefault(),
			false,
			'Default state toggles to false successfully'
		);

		// Reset
		model = new mw.rcfilters.dm.SavedQueryItemModel(
			'randomID',
			'Some label',
			$.extend( true, {}, itemData ),
			{ default: true }
		);

		assert.equal(
			model.isDefault(),
			true,
			'Default state represented when item initialized with default:true.'
		);
	} );
}( mediaWiki ) );
