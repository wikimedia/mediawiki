const HighlightColors = require( '../HighlightColors.js' );

/**
 * A widget representing a filter item highlight color picker.
 *
 * @class mw.rcfilters.ui.HighlightColorPickerWidget
 * @ignore
 * @extends OO.ui.Widget
 * @mixes OO.ui.mixin.LabelElement
 *
 * @param {mw.rcfilters.Controller} controller RCFilters controller
 * @param {Object} [config] Configuration object
 */
const HighlightColorPickerWidget = function MwRcfiltersUiHighlightColorPickerWidget( controller, config ) {
	const colors = [ 'none' ].concat( HighlightColors );
	config = config || {};

	// Parent
	HighlightColorPickerWidget.super.call( this, config );
	// Mixin constructors
	OO.ui.mixin.LabelElement.call( this, Object.assign( {}, config, {
		label: mw.msg( 'rcfilters-highlightmenu-title' )
	} ) );

	this.controller = controller;

	this.currentSelection = 'none';
	this.buttonSelect = new OO.ui.ButtonSelectWidget( {
		items: colors.map(
			// The following classes are used here:
			// * mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color-c1
			// * mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color-c2
			// * mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color-c3
			// * mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color-c4
			// * mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color-c5
			( color ) => new OO.ui.ButtonOptionWidget( {
				icon: color === 'none' ? 'check' : null,
				data: color,
				classes: [
					'mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color',
					'mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color-' + color
				],
				framed: false
			} )
		),
		classes: [ 'mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect' ]
	} );

	// Event
	this.buttonSelect.connect( this, { choose: 'onChooseColor' } );

	this.$element
		.addClass( 'mw-rcfilters-ui-highlightColorPickerWidget' )
		.append(
			this.$label
				.addClass( 'mw-rcfilters-ui-highlightColorPickerWidget-label' ),
			this.buttonSelect.$element
		);
};

/* Initialization */

OO.inheritClass( HighlightColorPickerWidget, OO.ui.Widget );
OO.mixinClass( HighlightColorPickerWidget, OO.ui.mixin.LabelElement );

/* Events */

/**
 * A color has been chosen
 *
 * @event chooseColor
 * @param {string} The chosen color
 * @ignore
 */

/* Methods */

/**
 * Bind the color picker to an item
 *
 * @param {mw.rcfilters.dm.FilterItem} filterItem
 */
HighlightColorPickerWidget.prototype.setFilterItem = function ( filterItem ) {
	if ( this.filterItem ) {
		this.filterItem.disconnect( this );
	}

	this.filterItem = filterItem;
	this.filterItem.connect( this, { update: 'updateUiBasedOnModel' } );
	this.updateUiBasedOnModel();
};

/**
 * Respond to item model update event
 */
HighlightColorPickerWidget.prototype.updateUiBasedOnModel = function () {
	this.selectColor( this.filterItem.getHighlightColor() || 'none' );
};

/**
 * Select the color for this widget
 *
 * @param {string} color Selected color
 */
HighlightColorPickerWidget.prototype.selectColor = function ( color ) {
	const previousItem = this.buttonSelect.findItemFromData( this.currentSelection ),
		selectedItem = this.buttonSelect.findItemFromData( color );

	if ( this.currentSelection !== color ) {
		this.currentSelection = color;

		this.buttonSelect.selectItem( selectedItem );
		if ( previousItem ) {
			previousItem.setIcon( null );
		}

		if ( selectedItem ) {
			selectedItem.setIcon( 'check' );
		}
	}
};

HighlightColorPickerWidget.prototype.onChooseColor = function ( button ) {
	const color = button.data;
	if ( color === 'none' ) {
		this.controller.clearHighlightColor( this.filterItem.getName() );
	} else {
		this.controller.setHighlightColor( this.filterItem.getName(), color );
	}
	this.emit( 'chooseColor', color );
};

module.exports = HighlightColorPickerWidget;
