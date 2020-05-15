/**
 * A widget representing a filter item highlight color picker
 *
 * @class mw.rcfilters.ui.HighlightColorPickerWidget
 * @extends OO.ui.Widget
 * @mixins OO.ui.mixin.LabelElement
 *
 * @constructor
 * @param {mw.rcfilters.Controller} controller RCFilters controller
 * @param {Object} [config] Configuration object
 */
var HighlightColorPickerWidget = function MwRcfiltersUiHighlightColorPickerWidget( controller, config ) {
	var colors = [ 'none' ].concat( mw.rcfilters.HighlightColors );
	config = config || {};

	// Parent
	HighlightColorPickerWidget.parent.call( this, config );
	// Mixin constructors
	OO.ui.mixin.LabelElement.call( this, $.extend( {}, config, {
		label: mw.message( 'rcfilters-highlightmenu-title' ).text()
	} ) );

	this.controller = controller;

	this.currentSelection = 'none';
	this.buttonSelect = new OO.ui.ButtonSelectWidget( {
		items: colors.map( function ( color ) {
			// The following classes are used here:
			// * mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color-c1
			// * mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color-c2
			// * mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color-c3
			// * mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color-c4
			// * mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color-c5
			return new OO.ui.ButtonOptionWidget( {
				icon: color === 'none' ? 'check' : null,
				data: color,
				classes: [
					'mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color',
					'mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect-color-' + color
				],
				framed: false
			} );
		} ),
		classes: 'mw-rcfilters-ui-highlightColorPickerWidget-buttonSelect'
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
 * @event chooseColor
 * @param {string} The chosen color
 *
 * A color has been chosen
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
	var previousItem = this.buttonSelect.findItemFromData( this.currentSelection ),
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
	var color = button.data;
	if ( color === 'none' ) {
		this.controller.clearHighlightColor( this.filterItem.getName() );
	} else {
		this.controller.setHighlightColor( this.filterItem.getName(), color );
	}
	this.emit( 'chooseColor', color );
};

module.exports = HighlightColorPickerWidget;
