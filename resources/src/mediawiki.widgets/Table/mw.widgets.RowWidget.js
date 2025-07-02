/**
 * @classdesc Table row widget. A RowWidget is used in conjunction with
 * {@link mw.widgets.TableWidget table widgets} and should not be instantiated by themselves.
 * They group together {@link OO.ui.TextInputWidget text input widgets} to form a unified row of
 * editable data.
 *
 * @class
 * @extends OO.ui.Widget
 * @mixes OO.ui.mixin.GroupElement
 *
 * @constructor
 * @description Create an instance of `mw.widgets.RowWidget`.
 * @param {Object} [config] Configuration options
 * @param {Array} [config.data] The data of the cells
 * @param {Array} [config.keys] An array of keys for easy cell selection
 * @param {RegExp|Function|string} [config.validate] Validation pattern to apply on every cell
 * @param {number} [config.index] The row index.
 * @param {string} [config.label] The row label to display. If not provided, the row index will
 * be used be default. If set to null, no label will be displayed.
 * @param {boolean} [config.showLabel=true] Show row label. Defaults to true.
 * @param {boolean} [config.deletable=true] Whether the table should provide deletion UI tools
 * for this row or not. Defaults to true.
 */
mw.widgets.RowWidget = function MwWidgetsRowWidget( config = {} ) {
	// Parent constructor
	mw.widgets.RowWidget.super.call( this, config );

	// Mixin constructor
	OO.ui.mixin.GroupElement.call( this, config );

	// Set up model
	this.model = new mw.widgets.RowWidgetModel( config );

	// Set up group element
	this.setGroupElement(
		$( '<div>' )
			.addClass( 'mw-widgets-rowWidget-cells' )
	);

	// Set up label
	this.labelCell = new OO.ui.LabelWidget( {
		classes: [ 'mw-widgets-rowWidget-label' ]
	} );

	// Set up delete button
	if ( this.model.getRowProperties().isDeletable ) {
		this.deleteButton = new OO.ui.ButtonWidget( {
			icon: 'trash',
			classes: [ 'mw-widgets-rowWidget-delete-button' ],
			flags: 'destructive',
			title: mw.msg( 'mw-widgets-table-row-delete' )
		} );
	}

	// Events
	this.model.connect( this, {
		valueChange: 'onValueChange',
		insertCell: 'onInsertCell',
		removeCell: 'onRemoveCell',
		clear: 'onClear',
		labelUpdate: 'onLabelUpdate'
	} );

	this.aggregate( {
		change: 'cellChange'
	} );

	this.connect( this, {
		cellChange: 'onCellChange'
	} );

	if ( this.model.getRowProperties().isDeletable ) {
		this.deleteButton.connect( this, {
			click: 'onDeleteButtonClick'
		} );
	}

	// Initialization
	this.$element.addClass( 'mw-widgets-rowWidget' );

	this.$element.append(
		this.labelCell.$element,
		this.$group
	);

	if ( this.model.getRowProperties().isDeletable ) {
		this.$element.append( this.deleteButton.$element );
	}

	this.setLabel( this.model.getRowProperties().label );

	this.model.setupRow();
};

/* Inheritance */

OO.inheritClass( mw.widgets.RowWidget, OO.ui.Widget );
OO.mixinClass( mw.widgets.RowWidget, OO.ui.mixin.GroupElement );

/* Events */

/**
 * Change when an input contained within the row is updated.
 *
 * @event mw.widgets.RowWidget.inputChange
 * @param {number} index The index of the cell that changed
 * @param {string} value The new value of the cell
 */

/**
 * Fired when the delete button for the row is pressed.
 *
 * @event mw.widgets.RowWidget.deleteButtonClick
 */

/* Methods */

/**
 * @private
 * @inheritdoc
 */
mw.widgets.RowWidget.prototype.addItems = function ( items, index ) {
	let i, len;

	OO.ui.mixin.GroupElement.prototype.addItems.call( this, items, index );

	for ( i = index, len = items.length; i < len; i++ ) {
		items[ i ].setData( i );
	}
};

/**
 * @private
 * @inheritdoc
 */
mw.widgets.RowWidget.prototype.removeItems = function ( items ) {
	OO.ui.mixin.GroupElement.prototype.removeItems.call( this, items );

	const cells = this.getItems();
	for ( let i = 0, len = cells.length; i < len; i++ ) {
		cells[ i ].setData( i );
	}
};

/**
 * Get the row index.
 *
 * @return {number} The row index
 */
mw.widgets.RowWidget.prototype.getIndex = function () {
	return this.model.getRowProperties().index;
};

/**
 * Set the row index.
 *
 * @param {number} index The new index
 */
mw.widgets.RowWidget.prototype.setIndex = function ( index ) {
	this.model.setIndex( index );
};

/**
 * Get the label displayed on the row. If no custom label is set, the
 * row index is used instead.
 *
 * @return {string} The row label
 */
mw.widgets.RowWidget.prototype.getLabel = function () {
	const props = this.model.getRowProperties();

	if ( props.label === null ) {
		return '';
	} else if ( !props.label ) {
		return props.index.toString();
	} else {
		return props.label;
	}
};

/**
 * @event mw.widgets.RowWidget.labelUpdate
 * @param {string} label
 */

/**
 * Set the label to be displayed on the widget.
 *
 * @param {string} label The new label
 * @fires mw.widgets.RowWidget.labelUpdate
 */
mw.widgets.RowWidget.prototype.setLabel = function ( label ) {
	this.model.setLabel( label );
};

/**
 * Set the value of a particular cell.
 *
 * @param {number} index The cell index
 * @param {string} value The new value
 */
mw.widgets.RowWidget.prototype.setValue = function ( index, value ) {
	this.model.setValue( index, value );
};

/**
 * Insert a cell at a specified index.
 *
 * @param  {string} data The cell data
 * @param  {number} index The index to insert the cell at
 * @param  {string} key A key for easy cell selection
 */
mw.widgets.RowWidget.prototype.insertCell = function ( data, index, key ) {
	this.model.insertCell( data, index, key );
};

/**
 * Removes a column at a specified index.
 *
 * @param {number} index The index to removeColumn
 */
mw.widgets.RowWidget.prototype.removeCell = function ( index ) {
	this.model.removeCell( index );
};

/**
 * Clear the field values.
 */
mw.widgets.RowWidget.prototype.clear = function () {
	this.model.clear();
};

/**
 * Handle model value changes.
 *
 * @param {number} index The column index of the updated cell
 * @param {number} value The new value
 *
 * @fires mw.widgets.RowWidget.inputChange
 */
mw.widgets.RowWidget.prototype.onValueChange = function ( index, value ) {
	this.getItems()[ index ].setValue( value );
	this.emit( 'inputChange', index, value );
};

/**
 * Handle model cell insertions.
 *
 * @param {string} data The initial data
 * @param {number} index The index in which to insert the new cell
 */
mw.widgets.RowWidget.prototype.onInsertCell = function ( data, index ) {
	this.addItems( [
		new OO.ui.TextInputWidget( {
			data: index,
			value: data,
			validate: this.model.getValidationPattern()
		} )
	], index );
};

/**
 * Handle model cell removals.
 *
 * @param {number} index The removed cell index
 */
mw.widgets.RowWidget.prototype.onRemoveCell = function ( index ) {
	this.removeItems( [ index ] );
};

/**
 * Handle clear requests.
 */
mw.widgets.RowWidget.prototype.onClear = function () {
	const cells = this.getItems();

	for ( let i = 0, len = cells.length; i < len; i++ ) {
		cells[ i ].setValue( '' );
	}
};

/**
 * Update model label changes.
 */
mw.widgets.RowWidget.prototype.onLabelUpdate = function () {
	this.labelCell.setLabel( this.getLabel() );
};

/**
 * React to cell input change.
 *
 * @private
 * @param {OO.ui.TextInputWidget} input The input that fired the event
 * @param {string} value The value of the input
 */
mw.widgets.RowWidget.prototype.onCellChange = function ( input, value ) {
	// FIXME: The table itself should know if it contains invalid data
	// in order to pass form state to the dialog when it asks if the Apply
	// button should be enabled or not. This probably requires the table
	// and each individual row to handle validation through an array of promises
	// fed from the cells within.
	// Right now, the table can't know if it's valid or not because the events
	// don't get passed through.
	input.getValidity().done( () => {
		this.model.setValue( input.getData(), value );
	} );
};

/**
 * Handle delete button clicks.
 *
 * @private
 * @fires mw.widgets.RowWidget.deleteButtonClick
 */
mw.widgets.RowWidget.prototype.onDeleteButtonClick = function () {
	this.emit( 'deleteButtonClick' );
};

/**
 * @inheritdoc
 */
mw.widgets.RowWidget.prototype.setDisabled = function ( disabled ) {
	// Parent method
	mw.widgets.RowWidget.super.prototype.setDisabled.call( this, disabled );

	if ( !this.items ) {
		return;
	}

	if ( this.model.getRowProperties().isDeletable ) {
		this.deleteButton.setDisabled( disabled );
	}

	this.getItems().forEach( ( cell ) => {
		cell.setDisabled( disabled );
	} );
};
