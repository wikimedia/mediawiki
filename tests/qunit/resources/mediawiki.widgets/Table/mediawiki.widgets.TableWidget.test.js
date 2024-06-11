/*!
 * MediaWiki Widgets TableWidget tests.
 */

QUnit.module( 'mediawiki.widgets.TableWidget' );

( function () {
	var widgetA = new mw.widgets.TableWidget( {
			rows: [
				{
					key: 'foo',
					label: 'Foo'
				},
				{
					key: 'bar',
					label: 'Bar'
				}
			],
			cols: [
				{
					label: 'A'
				},
				{
					label: 'B'
				}
			]
		} ),
		widgetB = new mw.widgets.TableWidget( {
			rows: [
				{
					key: 'foo'
				},
				{
					key: 'bar'
				}
			],
			cols: [
				{}, {}, {}
			],
			data: [
				[ '11', '12', '13' ],
				[ '21', '22', '23' ]
			]
		} ),
		widgetAexpectedRowProps = {
			index: 1,
			key: 'bar',
			label: 'Bar'
		},
		widgetAexpectedInitialData = [
			[ '', '' ],
			[ '', '' ]
		],
		widgetAexpectedDataAfterValue = [
			[ '', '' ],
			[ '3', '' ]
		],
		widgetAexpectedDataAfterRowColumnInsertions = [
			[ '', '', '' ],
			[ 'a', 'b', 'c' ],
			[ '3', '', '' ]
		],
		widgetAexpectedDataAfterColumnRemoval = [
			[ '', '' ],
			[ 'b', 'c' ],
			[ '', '' ]
		];

	// See https://phabricator.wikimedia.org/T151262#4253730 about skipped tests

	QUnit.test( 'TableWidgetModel initialization', ( assert ) => {
		assert.deepEqual( widgetA.model.data, widgetAexpectedInitialData, 'Table data is initialized properly' );
	} );

	QUnit.test( 'TableWidgetModel#getRowProperties', ( assert ) => {
		assert.deepEqual( widgetA.model.getRowProperties( 'bar' ), widgetAexpectedRowProps, 'Row props are returned successfully' );
	} );

	QUnit.test( 'TableWidget#setValue', ( assert ) => {
		widgetA.setValue( 'bar', 0, '3' );
		assert.deepEqual( widgetA.model.data, widgetAexpectedDataAfterValue, 'Table data is modified successfully' );
	} );

	QUnit.skip( 'TableWidget#insertColumn/insertRow', ( assert ) => {
		widgetA.insertColumn();
		widgetA.insertRow( [ 'a', 'b', 'c' ], 1, 'baz', 'Baz' );
		assert.deepEqual( widgetA.model.data, widgetAexpectedDataAfterRowColumnInsertions, 'Row and column are added successfully' );
	} );

	QUnit.skip( 'TableWidget#removeColumn', ( assert ) => {
		widgetA.removeColumn( 0 );
		assert.deepEqual( widgetA.model.data, widgetAexpectedDataAfterColumnRemoval, 'Columns are removed successfully' );
	} );

	QUnit.skip( 'TableWidget#removeRow by index', ( assert ) => {
		widgetA.removeRow( -1 );
		assert.deepEqual( widgetA.model.data, widgetAexpectedDataAfterColumnRemoval, 'Invalid row removal by index does not change table data' );
	} );

	QUnit.skip( 'TableWidget#removeRow by key', ( assert ) => {
		widgetA.removeRow( 'qux' );
		assert.deepEqual( widgetA.model.data, widgetAexpectedDataAfterColumnRemoval, 'Invalid row removal by key does not change table data' );
	} );

	QUnit.test( 'TableWidget populate text inputs', ( assert ) => {
		assert.deepEqual( widgetB.getItems()[ 0 ].getItems()[ 2 ].getValue(), '13', 'Initial data is populated in text inputs properly' );
	} );
}() );
