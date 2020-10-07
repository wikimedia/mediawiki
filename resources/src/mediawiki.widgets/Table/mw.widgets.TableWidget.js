/**
 * A TableWidget groups {@link mw.widgets.RowWidget row widgets} together to form a bidimensional
 * grid of text inputs.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixins OO.ui.mixin.GroupElement
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {Array} [rows] An array of objects containing `key` and `label` properties for every row
 * @cfg {Array} [cols] An array of objects containing `key` and `label` properties for every column
 * @cfg {Array} [data] An array containing all values of the table
 * @cfg {RegExp|Function|string} [validate] Validation pattern to apply on every cell
 * @cfg {boolean} [showHeaders=true] Whether or not to show table headers. Defaults to true.
 * @cfg {boolean} [showRowLabels=true] Whether or not to show row labels. Defaults to true.
 * @cfg {boolean} [allowRowInsertion=true] Whether or not to enable row insertion. Defaults to true.
 * @cfg {boolean} [allowRowDeletion=true] Allow row deletion. Defaults to true.
 */
mw.widgets.TableWidget = function MwWidgetsTableWidget( config ) {
	var headerRowItems = [],
		insertionRowItems = [],
		columnProps, prop, i, len;

	// Configuration initialization
	config = config || {};

	// Parent constructor
	mw.widgets.TableWidget.super.call( this, config );

	// Mixin constructors
	OO.ui.mixin.GroupElement.call( this, config );

	// Set up model
	this.model = new mw.widgets.TableWidgetModel( config );

	// Properties
	this.listeningToInsertionRowChanges = true;

	// Set up group element
	this.setGroupElement(
		$( '<div>' )
			.addClass( 'mw-widgets-tableWidget-rows' )
	);

	// Set up static rows
	columnProps = this.model.getAllColumnProperties();

	if ( this.model.getTableProperties().showHeaders ) {
		this.headerRow = new mw.widgets.RowWidget( {
			deletable: false,
			label: null
		} );

		for ( i = 0, len = columnProps.length; i < len; i++ ) {
			prop = columnProps[ i ];
			headerRowItems.push(
				this.getHeaderRowItem( prop.label, prop.key, prop.index )
			);
		}

		this.headerRow.addItems( headerRowItems );
	}

	if ( this.model.getTableProperties().allowRowInsertion ) {
		this.insertionRow = new mw.widgets.RowWidget( {
			classes: 'mw-widgets-rowWidget-insertionRow',
			deletable: false,
			label: null
		} );

		for ( i = 0, len = columnProps.length; i < len; i++ ) {
			insertionRowItems.push( new OO.ui.TextInputWidget( {
				data: columnProps[ i ].key ? columnProps[ i ].key : columnProps[ i ].index,
				disabled: this.isDisabled()
			} ) );
		}

		this.insertionRow.addItems( insertionRowItems );
	}

	// Set up initial rows
	if ( Array.isArray( config.items ) ) {
		this.addItems( config.items );
	}

	// Events
	this.model.connect( this, {
		valueChange: 'onValueChange',
		insertRow: 'onInsertRow',
		insertColumn: 'onInsertColumn',
		removeRow: 'onRemoveRow',
		removeColumn: 'onRemoveColumn',
		clear: 'onClear'
	} );

	this.aggregate( {
		inputChange: 'rowInputChange',
		deleteButtonClick: 'rowDeleteButtonClick'
	} );

	this.connect( this, {
		rowInputChange: 'onRowInputChange',
		rowDeleteButtonClick: 'onRowDeleteButtonClick'
	} );

	if ( this.model.getTableProperties().allowRowInsertion ) {
		this.insertionRow.connect( this, {
			inputChange: 'onInsertionRowInputChange'
		} );
	}

	// Initialization
	this.$element.addClass( 'mw-widgets-tableWidget' );

	if ( this.model.getTableProperties().showHeaders ) {
		this.$element.append( this.headerRow.$element );
	}
	this.$element.append( this.$group );
	if ( this.model.getTableProperties().allowRowInsertion ) {
		this.$element.append( this.insertionRow.$element );
	}

	this.$element.toggleClass(
		'mw-widgets-tableWidget-no-labels',
		!this.model.getTableProperties().showRowLabels
	);

	this.model.setupTable();
};

/* Inheritance */

OO.inheritClass( mw.widgets.TableWidget, OO.ui.Widget );
OO.mixinClass( mw.widgets.TableWidget, OO.ui.mixin.GroupElement );

/* Static Properties */
mw.widgets.TableWidget.static.patterns = {
	validate: /^[0-9]+(\.[0-9]+)?$/,
	filter: /[0-9]+(\.[0-9]+)?/
};

/* Events */

/**
 * @event change
 *
 * Change when the data within the table has been updated.
 *
 * @param {number} rowIndex The index of the row that changed
 * @param {string} rowKey The key of the row that changed, or `undefined` if it doesn't exist
 * @param {number} columnIndex The index of the column that changed
 * @param {string} columnKey The key of the column that changed, or `undefined` if it doesn't exist
 * @param {string} value The new value
 */

/**
 * @event removeRow
 *
 * Fires when a row is removed from the table
 *
 * @param {number} index The index of the row being deleted
 * @param {string} key The key of the row being deleted
 */

/* Methods */

/**
 * Set the value of a particular cell
 *
 * @param {number|string} row The row containing the cell to edit. Can be either
 * the row index or string key if one has been set for the row.
 * @param {number|string} col The column containing the cell to edit. Can be either
 * the column index or string key if one has been set for the column.
 * @param {Mixed} value The new value
 */
mw.widgets.TableWidget.prototype.setValue = function ( row, col, value ) {
	this.model.setValue( row, col, value );
};

/**
 * Set the table data
 *
 * @param {Array} data The new table data
 * @return {boolean} The data has been successfully changed
 */
mw.widgets.TableWidget.prototype.setData = function ( data ) {
	if ( !Array.isArray( data ) ) {
		return false;
	}

	this.model.setData( data );
	return true;
};

/**
 * Inserts a row into the table. If the row isn't added at the end of the table,
 * all the following data will be shifted back one row.
 *
 * @param {Array} [data] The data to insert to the row.
 * @param {number} [index] The index in which to insert the new row.
 * If unset or set to null, the row will be added at the end of the table.
 * @param {string} [key] A key to quickly select this row.
 * If unset or set to null, no key will be set.
 * @param {string} [label] A label to display next to the row.
 * If unset or set to null, the key will be used if there is one.
 */
mw.widgets.TableWidget.prototype.insertRow = function ( data, index, key, label ) {
	this.model.insertRow( data, index, key, label );
};

/**
 * Inserts a column into the table. If the column isn't added at the end of the table,
 * all the following data will be shifted back one column.
 *
 * @param {Array} [data] The data to insert to the column.
 * @param {number} [index] The index in which to insert the new column.
 * If unset or set to null, the column will be added at the end of the table.
 * @param {string} [key] A key to quickly select this column.
 * If unset or set to null, no key will be set.
 * @param {string} [label] A label to display next to the column.
 * If unset or set to null, the key will be used if there is one.
 */
mw.widgets.TableWidget.prototype.insertColumn = function ( data, index, key, label ) {
	this.model.insertColumn( data, index, key, label );
};

/**
 * Removes a row from the table. If the row removed isn't at the end of the table,
 * all the following rows will be shifted back one row.
 *
 * @param {number|string} key The key or numerical index of the row to remove.
 */
mw.widgets.TableWidget.prototype.removeRow = function ( key ) {
	this.model.removeRow( key );
};

/**
 * Removes a column from the table. If the column removed isn't at the end of the table,
 * all the following columns will be shifted back one column.
 *
 * @param {number|string} key The key or numerical index of the column to remove.
 */
mw.widgets.TableWidget.prototype.removeColumn = function ( key ) {
	this.model.removeColumn( key );
};

/**
 * Clears all values from the table, without wiping any row or column properties.
 */
mw.widgets.TableWidget.prototype.clear = function () {
	this.model.clear();
};

/**
 * Clears the table data, as well as all row and column properties
 */
mw.widgets.TableWidget.prototype.clearWithProperties = function () {
	this.model.clearWithProperties();
};

/**
 * Filter cell input once it is changed
 *
 * @param {string} value The input value
 * @return {string} The filtered input
 */
mw.widgets.TableWidget.prototype.filterCellInput = function ( value ) {
	var matches = value.match( mw.widgets.TableWidget.static.patterns.filter );
	return ( Array.isArray( matches ) ) ? matches[ 0 ] : '';
};

/**
 * Get an input item for the header row.
 *
 * @private
 * @param {string} label The column label
 * @param {string} key The column key
 * @param {number} index The column index
 * @return {OO.ui.TextInputWidget} An input item for the header row
 */
mw.widgets.TableWidget.prototype.getHeaderRowItem = function ( label, key, index ) {
	return new OO.ui.TextInputWidget( {
		value: label || key || index,
		// TODO: Allow editing of fields
		disabled: true
	} );
};

/**
 * @private
 * @inheritdoc
 */
mw.widgets.TableWidget.prototype.addItems = function ( items, index ) {
	var i, len;

	OO.ui.mixin.GroupElement.prototype.addItems.call( this, items, index );

	for ( i = index, len = items.length; i < len; i++ ) {
		items[ i ].setIndex( i );
	}
};

/**
 * @private
 * @inheritdoc
 */
mw.widgets.TableWidget.prototype.removeItems = function ( items ) {
	var i, len, rows;

	OO.ui.mixin.GroupElement.prototype.removeItems.call( this, items );

	rows = this.getItems();
	for ( i = 0, len = rows.length; i < len; i++ ) {
		rows[ i ].setIndex( i );
	}
};

/**
 * Handle model value changes
 *
 * @private
 * @param {number} row The row index of the changed cell
 * @param {number} col The column index of the changed cell
 * @param {Mixed} value The new value
 * @fires change
 */
mw.widgets.TableWidget.prototype.onValueChange = function ( row, col, value ) {
	var rowProps = this.model.getRowProperties( row ),
		colProps = this.model.getColumnProperties( col );

	this.getItems()[ row ].setValue( col, value );

	this.emit( 'change', row, rowProps.key, col, colProps.key, value );
};

/**
 * Handle model row insertions
 *
 * @private
 * @param {Array} data The initial data
 * @param {number} index The index in which the new row was inserted
 * @param {string} key The row key
 * @param {string} label The row label
 * @fires change
 */
mw.widgets.TableWidget.prototype.onInsertRow = function ( data, index, key, label ) {
	var colProps = this.model.getAllColumnProperties(),
		keys = [],
		newRow, i, len;

	for ( i = 0, len = colProps.length; i < len; i++ ) {
		keys.push( ( colProps[ i ].key ) ? colProps[ i ].key : i );
	}

	newRow = new mw.widgets.RowWidget( {
		data: data,
		keys: keys,
		validate: this.model.getValidationPattern(),
		label: label,
		showLabel: this.model.getTableProperties().showRowLabels,
		deletable: this.model.getTableProperties().allowRowDeletion
	} );

	newRow.setDisabled( this.isDisabled() );

	// TODO: Handle index parameter. Right now all new rows are inserted at the end
	this.addItems( [ newRow ] );

	// If this is the first data being added, refresh headers and insertion row
	if ( this.model.getAllRowProperties().length === 1 ) {
		this.refreshTableMarginals();
	}

	for ( i = 0, len = data.length; i < len; i++ ) {
		this.emit( 'change', index, key, i, colProps[ i ].key, data[ i ] );
	}
};

/**
 * Handle model column insertions
 *
 * @private
 * @param {Array} data The initial data
 * @param {number} index The index in which to insert the new column
 * @param {string} key The row key
 * @param {string} label The row label
 *
 * @fires change
 */
mw.widgets.TableWidget.prototype.onInsertColumn = function ( data, index, key, label ) {
	var tableProps = this.model.getTableProperties(),
		items = this.getItems(),
		rowProps = this.model.getAllRowProperties(),
		i, len;

	for ( i = 0, len = items.length; i < len; i++ ) {
		items[ i ].insertCell( data[ i ], index, key );
		this.emit( 'change', i, rowProps[ i ].key, index, key, data[ i ] );
	}

	if ( tableProps.showHeaders ) {
		this.headerRow.addItems( [
			this.getHeaderRowItem( label, key, index )
		] );
	}

	if ( tableProps.handleRowInsertion ) {
		this.insertionRow.addItems( [
			new OO.ui.TextInputWidget( {
				validate: this.model.getValidationPattern(),
				disabled: this.isDisabled()
			} )
		] );
	}
};

/**
 * Handle model row removals
 *
 * @private
 * @param {number} index The removed row index
 * @param {string} key The removed row key
 * @fires removeRow
 */
mw.widgets.TableWidget.prototype.onRemoveRow = function ( index, key ) {
	this.removeItems( [ this.getItems()[ index ] ] );
	this.emit( 'removeRow', index, key );
};

/**
 * Handle model column removals
 *
 * @private
 * @param {number} index The removed column index
 * @param {string} key The removed column key
 * @fires removeColumn
 */
mw.widgets.TableWidget.prototype.onRemoveColumn = function ( index, key ) {
	var i, items = this.getItems();

	for ( i = 0; i < items.length; i++ ) {
		items[ i ].removeCell( index );
	}

	this.emit( 'removeColumn', index, key );
};

/**
 * Handle model table clears
 *
 * @private
 * @param {boolean} withProperties Clear row/column properties
 */
mw.widgets.TableWidget.prototype.onClear = function ( withProperties ) {
	var i, len, rows;

	if ( withProperties ) {
		this.removeItems( this.getItems() );
	} else {
		rows = this.getItems();

		for ( i = 0, len = rows.length; i < len; i++ ) {
			rows[ i ].clear();
		}
	}
};

/**
 * React to input changes bubbled up from event aggregation
 *
 * @private
 * @param {mw.widgets.RowWidget} row The row that changed
 * @param {number} colIndex The column index of the cell that changed
 * @param {string} value The new value of the input
 * @fires change
 */
mw.widgets.TableWidget.prototype.onRowInputChange = function ( row, colIndex, value ) {
	var items = this.getItems(),
		i, len, rowIndex;

	for ( i = 0, len = items.length; i < len; i++ ) {
		if ( row === items[ i ] ) {
			rowIndex = i;
			break;
		}
	}

	this.model.setValue( rowIndex, colIndex, value );
};

/**
 * React to new row input changes
 *
 * @private
 * @param {number} colIndex The column index of the input that fired the change
 * @param {string} value The new row value
 */
mw.widgets.TableWidget.prototype.onInsertionRowInputChange = function ( colIndex, value ) {
	var insertionRowItems = this.insertionRow.getItems(),
		newRowData = [],
		i, len, lastRow;

	if ( this.listeningToInsertionRowChanges ) {
		for ( i = 0, len = insertionRowItems.length; i < len; i++ ) {
			if ( i === colIndex ) {
				newRowData.push( value );
			} else {
				newRowData.push( '' );
			}
		}

		this.insertRow( newRowData );

		// Focus newly inserted row
		lastRow = this.getItems().slice( -1 )[ 0 ];
		lastRow.getItems()[ colIndex ].focus();

		// Reset insertion row
		this.listeningToInsertionRowChanges = false;
		this.insertionRow.clear();
		this.listeningToInsertionRowChanges = true;
	}
};

/**
 * Handle row deletion input
 *
 * @private
 * @param {mw.widgets.RowWidget} row The row that asked for the deletion
 */
mw.widgets.TableWidget.prototype.onRowDeleteButtonClick = function ( row ) {
	var items = this.getItems(),
		i = -1,
		len;

	for ( i = 0, len = items.length; i < len; i++ ) {
		if ( items[ i ] === row ) {
			break;
		}
	}

	this.removeRow( i );
};

/**
 * @inheritdoc
 */
mw.widgets.TableWidget.prototype.setDisabled = function ( disabled ) {
	// Parent method
	mw.widgets.TableWidget.super.prototype.setDisabled.call( this, disabled );

	if ( !this.items ) {
		return;
	}

	this.getItems().forEach( function ( row ) {
		row.setDisabled( disabled );
	} );

	if ( this.model.getTableProperties().allowRowInsertion ) {
		this.insertionRow.getItems().forEach( function ( row ) {
			row.setDisabled( disabled );
		} );
	}
};

/**
 * Refresh table header and insertion row
 */
mw.widgets.TableWidget.prototype.refreshTableMarginals = function () {
	var tableProps = this.model.getTableProperties(),
		columnProps = this.model.getAllColumnProperties(),
		rowItems,
		i, len, prop;

	if ( tableProps.showHeaders ) {
		this.headerRow.removeItems( this.headerRow.getItems() );
		rowItems = [];

		for ( i = 0, len = columnProps.length; i < len; i++ ) {
			prop = columnProps[ i ];
			rowItems.push(
				this.getHeaderRowItem( prop.label, prop.key, prop.index )
			);
		}

		this.headerRow.addItems( rowItems );
	}

	if ( tableProps.allowRowInsertion ) {
		this.insertionRow.clear();
		this.insertionRow.removeItems( this.insertionRow.getItems() );

		for ( i = 0, len = columnProps.length; i < len; i++ ) {
			this.insertionRow.insertCell( '', columnProps[ i ].index, columnProps[ i ].key );
		}
	}
};
