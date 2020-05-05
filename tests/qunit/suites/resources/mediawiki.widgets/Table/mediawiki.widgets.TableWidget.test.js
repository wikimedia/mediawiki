/*!
 * MediaWiki Widgets TableWidget tests.
 */

QUnit.module( 'mediawiki.widgets.TableWidget' );

( function () {
	// The test verifes columns can be inserted and seem valid. However the
	// code itself is bugged and columns end up duplicated.
	//
	// See https://phabricator.wikimedia.org/T151262#4253730
	QUnit.skip( 'mw.widgets.TableWidget', function ( assert ) {
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

		assert.deepEqual( widgetA.model.data, widgetAexpectedInitialData, 'Table data is initialized properly' );

		assert.deepEqual( widgetA.model.getRowProperties( 'bar' ), widgetAexpectedRowProps, 'Row props are returned successfully' );

		widgetA.setValue( 'bar', 0, '3' );
		assert.deepEqual( widgetA.model.data, widgetAexpectedDataAfterValue, 'Table data is modified successfully' );

		widgetA.insertColumn();
		widgetA.insertRow( [ 'a', 'b', 'c' ], 1, 'baz', 'Baz' );
		assert.deepEqual( widgetA.model.data, widgetAexpectedDataAfterRowColumnInsertions, 'Row and column are added successfully' );

		widgetA.removeColumn( 0 );
		assert.deepEqual( widgetA.model.data, widgetAexpectedDataAfterColumnRemoval, 'Columns are removed successfully' );

		widgetA.removeRow( -1 );
		assert.deepEqual( widgetA.model.data, widgetAexpectedDataAfterColumnRemoval, 'Invalid row removal by index does not change table data' );

		widgetA.removeRow( 'qux' );
		assert.deepEqual( widgetA.model.data, widgetAexpectedDataAfterColumnRemoval, 'Invalid row removal by key does not change table data' );

		assert.deepEqual( widgetB.getItems()[ 0 ].getItems()[ 2 ].getValue(), '13', 'Initial data is populated in text inputs properly' );
	} );
}() );
