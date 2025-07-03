/*!
 * MediaWiki Widgets TableWidgetModel class.
 *
 * @license The MIT License (MIT); see LICENSE.txt
 */

/**
 * Configuration options.
 *
 * @typedef {Object} mw.widgets.TableWidgetModel~Config
 * @property {Array} [rows] An array of objects containing `key` and `label` properties for every row
 * @property {Array} [cols] An array of objects containing `key` and `label` properties for every column
 * @property {Array} [data] An array containing all values of the table
 * @property {RegExp|Function|string} [validate] Validation pattern to apply on every cell
 * @property {boolean} [showHeaders=true] Show table header row. Defaults to true.
 * @property {boolean} [showRowLabels=true] Show row labels. Defaults to true.
 * @property {boolean} [allowRowInsertion=true] Allow row insertion. Defaults to true.
 * @property {boolean} [allowRowDeletion=true] Allow row deletion. Defaults to true.
 */

/**
 * @classdesc TableWidget model.
 *
 * @class
 * @mixes OO.EventEmitter
 *
 * @constructor
 * @description Create an instance of `mw.widgets.TableWidgetModel`.
 * @param {mw.widgets.TableWidgetModel~Config} [config] Configuration options
 */
mw.widgets.TableWidgetModel = function MwWidgetsTableWidgetModel( config = {} ) {
	// Mixin constructors
	OO.EventEmitter.call( this, config );

	this.data = config.data || [];
	this.validate = config.validate;
	this.showHeaders = ( config.showHeaders !== undefined ) ? !!config.showHeaders : true;
	this.showRowLabels = ( config.showRowLabels !== undefined ) ? !!config.showRowLabels : true;
	this.allowRowInsertion = ( config.allowRowInsertion !== undefined ) ?
		!!config.allowRowInsertion : true;
	this.allowRowDeletion = ( config.allowRowDeletion !== undefined ) ?
		!!config.allowRowDeletion : true;

	this.initializeProps( config.rows, config.cols );
};

/* Inheritance */

OO.mixinClass( mw.widgets.TableWidgetModel, OO.EventEmitter );

/* Static Methods */

/**
 * Get an entry from a props table
 *
 * @static
 * @private
 * @param {string|number} handle The key (or numeric index) of the row/column
 * @param {Array} table Props table
 * @return {Object|null} An object containing the `key`, `index` and `label`
 * properties of the row/column. Returns `null` if the row/column can't be found.
 */
mw.widgets.TableWidgetModel.static.getEntryFromPropsTable = function ( handle, table ) {
	let row = null,
		i, len;

	if ( typeof handle === 'string' ) {
		for ( i = 0, len = table.length; i < len; i++ ) {
			if ( table[ i ].key === handle ) {
				row = table[ i ];
				break;
			}
		}
	} else if ( typeof handle === 'number' ) {
		if ( handle < table.length ) {
			row = table[ handle ];
		}
	}

	return row;
};

/* Events */

/**
 * Fired when a value inside the table has changed.
 *
 * @event mw.widgets.TableWidgetModel.valueChange
 * @param {number} row The row index of the updated cell
 * @param {number} column The column index of the updated cell
 * @param {any} value The new value
 */

/**
 * Fired when a new row is inserted into the table.
 *
 * @event mw.widgets.TableWidgetModel.insertRow
 * @param {Array} data The initial data
 * @param {number} index The index in which to insert the new row
 * @param {string} key The row key
 * @param {string} label The row label
 */

/**
 * Fired when a new row is inserted into the table.
 *
 * @event mw.widgets.TableWidgetModel.insertColumn
 * @param {Array} data The initial data
 * @param {number} index The index in which to insert the new column
 * @param {string} key The column key
 * @param {string} label The column label
 */

/**
 * Fired when a row is removed from the table.
 *
 * @event mw.widgets.TableWidgetModel.removeRow
 * @param {number} index The removed row index
 * @param {string} key The removed row key
 */

/**
 * Fired when a column is removed from the table.
 *
 * @event mw.widgets.TableWidgetModel.removeColumn
 * @param {number} index The removed column index
 * @param {string} key The removed column key
 */

/**
 * Fired when the table data is wiped.
 *
 * @event mw.widgets.TableWidgetModel.clear
 * @param {boolean} clear Clear row/column properties
 */

/* Methods */

/**
 * Initializes and ensures the proper creation of the rows and cols property arrays.
 * If data exceeds the number of rows and cols given, new ones will be created.
 *
 * @private
 * @param {Array} rowProps The initial row props
 * @param {Array} colProps The initial column props
 */
mw.widgets.TableWidgetModel.prototype.initializeProps = function ( rowProps, colProps ) {
	// FIXME: Account for extra data with missing row/col metadata

	let i, len;

	this.rows = [];
	this.cols = [];

	if ( Array.isArray( rowProps ) ) {
		for ( i = 0, len = rowProps.length; i < len; i++ ) {
			this.rows.push( {
				index: i,
				key: rowProps[ i ].key,
				label: rowProps[ i ].label
			} );
		}
	}

	if ( Array.isArray( colProps ) ) {
		for ( i = 0, len = colProps.length; i < len; i++ ) {
			this.cols.push( {
				index: i,
				key: colProps[ i ].key,
				label: colProps[ i ].label
			} );
		}
	}
};

/**
 * Triggers the initialization process and builds the initial table.
 *
 * @fires mw.widgets.TableWidgetModel.insertRow
 */
mw.widgets.TableWidgetModel.prototype.setupTable = function () {
	this.verifyData();
	this.buildTable();
};

/**
 * Verifies if the table data is complete and synced with
 * row and column properties, and adds empty strings as
 * cell data if cells are missing
 *
 * @private
 */
mw.widgets.TableWidgetModel.prototype.verifyData = function () {
	let i, j, rowLen, colLen;

	for ( i = 0, rowLen = this.rows.length; i < rowLen; i++ ) {
		if ( this.data[ i ] === undefined ) {
			this.data.push( [] );
		}

		for ( j = 0, colLen = this.cols.length; j < colLen; j++ ) {
			if ( this.data[ i ][ j ] === undefined ) {
				this.data[ i ].push( '' );
			}
		}
	}
};

/**
 * Build initial table
 *
 * @private
 * @fires mw.widgets.TableWidgetModel.insertRow
 */
mw.widgets.TableWidgetModel.prototype.buildTable = function () {
	let i, len;

	for ( i = 0, len = this.rows.length; i < len; i++ ) {
		this.emit( 'insertRow', this.data[ i ], i, this.rows[ i ].key, this.rows[ i ].label );
	}
};

/**
 * Refresh the entire table with new data
 *
 * @private
 * @fires mw.widgets.TableWidgetModel.insertRow
 */
mw.widgets.TableWidgetModel.prototype.refreshTable = function () {
	// TODO: Clear existing table

	this.buildTable();
};

/**
 * Set the value of a particular cell.
 *
 * @param {number|string} row The index or key of the row
 * @param {number|string} col The index or key of the column
 * @param {any} value The new value
 * @fires mw.widgets.TableWidgetModel.valueChange
 */
mw.widgets.TableWidgetModel.prototype.setValue = function ( row, col, value ) {
	let rowIndex, colIndex;

	if ( typeof row === 'number' ) {
		rowIndex = row;
	} else if ( typeof row === 'string' ) {
		rowIndex = this.getRowProperties( row ).index;
	}

	if ( typeof col === 'number' ) {
		colIndex = col;
	} else if ( typeof col === 'string' ) {
		colIndex = this.getColumnProperties( col ).index;
	}

	if ( typeof rowIndex === 'number' && typeof colIndex === 'number' &&
		this.data[ rowIndex ] !== undefined && this.data[ rowIndex ][ colIndex ] !== undefined &&
		this.data[ rowIndex ][ colIndex ] !== value ) {

		this.data[ rowIndex ][ colIndex ] = value;
		this.emit( 'valueChange', rowIndex, colIndex, value );
	}
};

/**
 * Set the table data.
 *
 * @param {Array} data The new table data
 */
mw.widgets.TableWidgetModel.prototype.setData = function ( data ) {
	if ( Array.isArray( data ) ) {
		this.data = data;

		this.verifyData();
		this.refreshTable();
	}
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
 * @fires mw.widgets.TableWidgetModel.insertRow
 */
mw.widgets.TableWidgetModel.prototype.insertRow = function ( data, index, key, label ) {
	const insertIndex = ( typeof index === 'number' ) ? index : this.rows.length;

	// Add the new row metadata
	this.rows.splice( insertIndex, 0, {
		index: insertIndex,
		key: key || undefined,
		label: label || undefined
	} );

	const newRowData = [];
	let insertDataCell;

	// Add the new row data
	const insertData = ( Array.isArray( data ) ) ? data : [];
	// Ensure that all columns of data for this row have been supplied,
	// otherwise fill the remaining data with empty strings

	for ( let i = 0, len = this.cols.length; i < len; i++ ) {
		insertDataCell = '';
		if ( typeof insertData[ i ] === 'string' || typeof insertData[ i ] === 'number' ) {
			insertDataCell = insertData[ i ];
		}

		newRowData.push( insertDataCell );
	}
	this.data.splice( insertIndex, 0, newRowData );

	// Update all indexes in following rows
	for ( let i = insertIndex + 1, len = this.rows.length; i < len; i++ ) {
		this.rows[ i ].index++;
	}

	this.emit( 'insertRow', data, insertIndex, key, label );
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
 * @fires mw.widgets.TableWidgetModel.insertColumn
 */
mw.widgets.TableWidgetModel.prototype.insertColumn = function ( data, index, key, label ) {
	const insertIndex = ( typeof index === 'number' ) ? index : this.cols.length;

	// Add the new column metadata
	this.cols.splice( insertIndex, 0, {
		index: insertIndex,
		key: key || undefined,
		label: label || undefined
	} );

	// Add the new column data
	const insertData = ( Array.isArray( data ) ) ? data : [];
	// Ensure that all rows of data for this column have been supplied,
	// otherwise fill the remaining data with empty strings

	let insertDataCell;

	for ( let i = 0, len = this.rows.length; i < len; i++ ) {
		insertDataCell = '';
		if ( typeof insertData[ i ] === 'string' || typeof insertData[ i ] === 'number' ) {
			insertDataCell = insertData[ i ];
		}

		this.data[ i ].splice( insertIndex, 0, insertDataCell );
	}

	// Update all indexes in following cols
	for ( let i = insertIndex + 1, len = this.cols.length; i < len; i++ ) {
		this.cols[ i ].index++;
	}

	this.emit( 'insertColumn', data, index, key, label );
};

/**
 * Removes a row from the table. If the row removed isn't at the end of the table,
 * all the following rows will be shifted back one row.
 *
 * @param {number|string} handle The key or numerical index of the row to remove
 * @fires mw.widgets.TableWidgetModel.removeRow
 */
mw.widgets.TableWidgetModel.prototype.removeRow = function ( handle ) {
	const rowProps = this.getRowProperties( handle );

	// Exit early if the row couldn't be found
	if ( rowProps === null ) {
		return;
	}

	this.rows.splice( rowProps.index, 1 );
	this.data.splice( rowProps.index, 1 );

	// Update all indexes in following rows
	for ( let i = rowProps.index, len = this.rows.length; i < len; i++ ) {
		this.rows[ i ].index--;
	}

	this.emit( 'removeRow', rowProps.index, rowProps.key );
};

/**
 * Removes a column from the table. If the column removed isn't at the end of the table,
 * all the following columns will be shifted back one column.
 *
 * @param {number|string} handle The key or numerical index of the column to remove
 * @fires mw.widgets.TableWidgetModel.removeColumn
 */
mw.widgets.TableWidgetModel.prototype.removeColumn = function ( handle ) {
	const colProps = this.getColumnProperties( handle );

	// Exit early if the column couldn't be found
	if ( colProps === null ) {
		return;
	}

	this.cols.splice( colProps.index, 1 );

	for ( let i = 0, len = this.data.length; i < len; i++ ) {
		this.data[ i ].splice( colProps.index, 1 );
	}

	// Update all indexes in following columns
	for ( let i = colProps.index, len = this.cols.length; i < len; i++ ) {
		this.cols[ i ].index--;
	}

	this.emit( 'removeColumn', colProps.index, colProps.key );
};

/**
 * Clears the table data.
 *
 * @fires mw.widgets.TableWidgetModel.clear
 */
mw.widgets.TableWidgetModel.prototype.clear = function () {
	this.data = [];
	this.verifyData();

	this.emit( 'clear', false );
};

/**
 * Clears the table data, as well as all row and column properties.
 *
 * @fires mw.widgets.TableWidgetModel.clear
 */
mw.widgets.TableWidgetModel.prototype.clearWithProperties = function () {
	this.data = [];
	this.rows = [];
	this.cols = [];

	this.emit( 'clear', true );
};

/**
 * Get all table properties.
 *
 * @return {Object}
 */
mw.widgets.TableWidgetModel.prototype.getTableProperties = function () {
	return {
		showHeaders: this.showHeaders,
		showRowLabels: this.showRowLabels,
		allowRowInsertion: this.allowRowInsertion,
		allowRowDeletion: this.allowRowDeletion
	};
};

/**
 * Get the validation pattern to test cells against.
 *
 * @return {RegExp|Function|string}
 */
mw.widgets.TableWidgetModel.prototype.getValidationPattern = function () {
	return this.validate;
};

/**
 * Get properties of a given row.
 *
 * @param {string|number} handle The key (or numeric index) of the row
 * @return {Object|null} An object containing the `key`, `index` and `label` properties of the row.
 * Returns `null` if the row can't be found.
 */
mw.widgets.TableWidgetModel.prototype.getRowProperties = function ( handle ) {
	return mw.widgets.TableWidgetModel.static.getEntryFromPropsTable( handle, this.rows );
};

/**
 * Get properties of all rows.
 *
 * @return {Array} An array of objects containing `key`, `index` and `label` properties for each row
 */
mw.widgets.TableWidgetModel.prototype.getAllRowProperties = function () {
	return this.rows.slice();
};

/**
 * Get properties of a given column.
 *
 * @param {string|number} handle The key (or numeric index) of the column
 * @return {Object|null} An object containing the `key`, `index` and
 *  `label` properties of the column.
 * Returns `null` if the column can't be found.
 */
mw.widgets.TableWidgetModel.prototype.getColumnProperties = function ( handle ) {
	return mw.widgets.TableWidgetModel.static.getEntryFromPropsTable( handle, this.cols );
};

/**
 * Get properties of all columns.
 *
 * @return {Array} An array of objects containing `key`, `index` and
 *  `label` properties for each column
 */
mw.widgets.TableWidgetModel.prototype.getAllColumnProperties = function () {
	return this.cols.slice();
};
