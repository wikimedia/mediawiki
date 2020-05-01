/*!
 * MediaWiki Widgets RowWidgetModel class
 *
 * @license The MIT License (MIT); see LICENSE.txt
 */

/**
 * RowWidget model.
 *
 * @class
 * @mixins OO.EventEmitter
 *
 * @constructor
 * @param {Object} [config] Configuration options
 * @cfg {Array} [data] An array containing all values of the row
 * @cfg {Array} [keys] An array of keys for easy cell selection
 * @cfg {RegExp|Function|string} [validate] Validation pattern to apply on every cell
 * @cfg {string} [label=''] Row label. Defaults to empty string.
 * @cfg {boolean} [showLabel=true] Show row label. Defaults to true.
 * @cfg {boolean} [deletable=true] Allow row to be deleted. Defaults to true.
 */
mw.widgets.RowWidgetModel = function MwWidgetsRowWidgetModel( config ) {
	config = config || {};

	// Mixin constructors
	OO.EventEmitter.call( this, config );

	this.data = config.data || [];
	this.validate = config.validate;
	this.index = ( config.index !== undefined ) ? config.index : -1;
	this.label = ( config.label !== undefined ) ? config.label : '';
	this.showLabel = ( config.showLabel !== undefined ) ? !!config.showLabel : true;
	this.isDeletable = ( config.deletable !== undefined ) ? !!config.deletable : true;

	this.initializeProps( config.keys );
};

/* Inheritance */

OO.mixinClass( mw.widgets.RowWidgetModel, OO.EventEmitter );

/* Events */

/**
 * @event valueChange
 *
 * Fired when a value inside the row has changed.
 *
 * @param {number} index The column index of the updated cell
 * @param {number} value The new value
 */

/**
 * @event insertCell
 *
 * Fired when a new cell is inserted into the row.
 *
 * @param {Array} data The initial data
 * @param {number} index The index in which to insert the new cell
 */

/**
 * @event removeCell
 *
 * Fired when a cell is removed from the row.
 *
 * @param {number} index The removed cell index
 */

/**
 * @event clear
 *
 * Fired when the row is cleared
 *
 * @param {boolean} clear Clear cell properties
 */

/**
 * @event labelUpdate
 *
 * Fired when the row label might need to be updated
 */

/* Methods */

/**
 * Initializes and ensures the proper creation of the cell property array.
 * If data exceeds the number of cells given, new ones will be created.
 *
 * @private
 * @param {Array} props The initial cell props
 */
mw.widgets.RowWidgetModel.prototype.initializeProps = function ( props ) {
	var i, len;

	this.cells = [];

	if ( Array.isArray( props ) ) {
		for ( i = 0, len = props.length; i < len; i++ ) {
			this.cells.push( {
				index: i,
				key: props[ i ]
			} );
		}
	}
};

/**
 * Triggers the initialization process and builds the initial row.
 *
 * @fires insertCell
 */
mw.widgets.RowWidgetModel.prototype.setupRow = function () {
	this.verifyData();
	this.buildRow();
};

/**
 * Verifies if the table data is complete and synced with
 * cell properties, and adds empty strings as cell data if
 * cells are missing
 *
 * @private
 */
mw.widgets.RowWidgetModel.prototype.verifyData = function () {
	var i, len;

	for ( i = 0, len = this.cells.length; i < len; i++ ) {
		if ( this.data[ i ] === undefined ) {
			this.data.push( '' );
		}
	}
};

/**
 * Build initial row
 *
 * @private
 * @fires insertCell
 */
mw.widgets.RowWidgetModel.prototype.buildRow = function () {
	var i, len;

	for ( i = 0, len = this.cells.length; i < len; i++ ) {
		this.emit( 'insertCell', this.data[ i ], i );
	}
};

/**
 * Refresh the entire row with new data
 *
 * @private
 * @fires insertCell
 */
mw.widgets.RowWidgetModel.prototype.refreshRow = function () {
	// TODO: Clear existing table

	this.buildRow();
};

/**
 * Set the value of a particular cell
 *
 * @param {number|string} handle The index or key of the cell
 * @param {Mixed} value The new value
 * @fires valueChange
 */
mw.widgets.RowWidgetModel.prototype.setValue = function ( handle, value ) {
	var index;

	if ( typeof handle === 'number' ) {
		index = handle;
	} else if ( typeof handle === 'string' ) {
		index = this.getCellProperties( handle ).index;
	}

	if ( typeof index === 'number' && this.data[ index ] !== undefined &&
		this.data[ index ] !== value ) {

		this.data[ index ] = value;
		this.emit( 'valueChange', index, value );
	}
};

/**
 * Set the row data
 *
 * @param {Array} data The new row data
 */
mw.widgets.RowWidgetModel.prototype.setData = function ( data ) {
	if ( Array.isArray( data ) ) {
		this.data = data;

		this.verifyData();
		this.refreshRow();
	}
};

/**
 * Set the row index
 *
 * @param {number} index The new row index
 * @fires labelUpdate
 */
mw.widgets.RowWidgetModel.prototype.setIndex = function ( index ) {
	this.index = index;
	this.emit( 'labelUpdate' );
};

/**
 * Set the row label
 *
 * @param {number} label The new row label
 * @fires labelUpdate
 */
mw.widgets.RowWidgetModel.prototype.setLabel = function ( label ) {
	this.label = label;
	this.emit( 'labelUpdate' );
};

/**
 * Inserts a row into the table. If the row isn't added at the end of the table,
 * all the following data will be shifted back one row.
 *
 * @param {number|string} [data] The data to insert to the cell.
 * @param {number} [index] The index in which to insert the new cell.
 * If unset or set to null, the cell will be added at the end of the row.
 * @param {string} [key] A key to quickly select this cell.
 * If unset or set to null, no key will be set.
 * @fires insertCell
 */
mw.widgets.RowWidgetModel.prototype.insertCell = function ( data, index, key ) {
	var insertIndex = ( typeof index === 'number' ) ? index : this.cells.length,
		insertData, i, len;

	// Add the new cell metadata
	this.cells.splice( insertIndex, 0, {
		index: insertIndex,
		key: key || undefined
	} );

	// Add the new row data
	insertData = ( typeof data === 'string' || typeof data === 'number' ) ? data : '';
	this.data.splice( insertIndex, 0, insertData );

	// Update all indexes in following cells
	for ( i = insertIndex + 1, len = this.cells.length; i < len; i++ ) {
		this.cells[ i ].index++;
	}

	this.emit( 'insertCell', data, insertIndex );
};

/**
 * Removes a cell from the table. If the cell removed isn't at the end of the table,
 * all the following  cells will be shifted back one cell.
 *
 * @param {number|string} handle The key or numerical index of the cell to remove
 * @fires removeCell
 */
mw.widgets.RowWidgetModel.prototype.removeCell = function ( handle ) {
	var cellProps = this.getCellProperties( handle ),
		i, len;

	// Exit early if the row couldn't be found
	if ( cellProps === null ) {
		return;
	}

	this.cells.splice( cellProps.index, 1 );
	this.data.splice( cellProps.index, 1 );

	// Update all indexes in following cells
	for ( i = cellProps.index, len = this.cells.length; i < len; i++ ) {
		this.cells[ i ].index--;
	}

	this.emit( 'removeCell', cellProps.index );
};

/**
 * Clears the row data
 *
 * @fires clear
 */
mw.widgets.RowWidgetModel.prototype.clear = function () {
	this.data = [];
	this.verifyData();

	this.emit( 'clear', false );
};

/**
 * Clears the row data, as well as all cell properties
 *
 * @fires clear
 */
mw.widgets.RowWidgetModel.prototype.clearWithProperties = function () {
	this.data = [];
	this.cells = [];

	this.emit( 'clear', true );
};

/**
 * Get the validation pattern to test cells against
 *
 * @return {RegExp|Function|string}
 */
mw.widgets.RowWidgetModel.prototype.getValidationPattern = function () {
	return this.validate;
};

/**
 * Get all row properties
 *
 * @return {Object}
 */
mw.widgets.RowWidgetModel.prototype.getRowProperties = function () {
	return {
		index: this.index,
		label: this.label,
		showLabel: this.showLabel,
		isDeletable: this.isDeletable
	};
};

/**
 * Get properties of a given cell
 *
 * @param {string|number} handle The key (or numeric index) of the cell
 * @return {Object|null} An object containing the `key` and `index` properties of the cell.
 * Returns `null` if the cell can't be found.
 */
mw.widgets.RowWidgetModel.prototype.getCellProperties = function ( handle ) {
	var cell = null,
		i, len;

	if ( typeof handle === 'string' ) {
		for ( i = 0, len = this.cells.length; i < len; i++ ) {
			if ( this.cells[ i ].key === handle ) {
				cell = this.cells[ i ];
				break;
			}
		}
	} else if ( typeof handle === 'number' ) {
		if ( handle < this.cells.length ) {
			cell = this.cells[ handle ];
		}
	}

	return cell;
};

/**
 * Get properties of all cells
 *
 * @return {Array} An array of objects containing `key` and `index` properties for each cell
 */
mw.widgets.RowWidgetModel.prototype.getAllCellProperties = function () {
	return this.cells.slice();
};
