var HighlightColorPickerWidget = require( './HighlightColorPickerWidget.js' ),
	HighlightPopupWidget;
/**
 * A popup containing a color picker, for setting highlight colors.
 *
 * @class mw.rcfilters.ui.HighlightPopupWidget
 * @extends OO.ui.PopupWidget
 *
 * @constructor
 * @param {mw.rcfilters.Controller} controller RCFilters controller
 * @param {Object} [config] Configuration object
 */
HighlightPopupWidget = function MwRcfiltersUiHighlightPopupWidget( controller, config ) {
	config = config || {};

	// Parent
	HighlightPopupWidget.parent.call( this, $.extend( {
		autoClose: true,
		anchor: false,
		padded: true,
		align: 'backwards',
		horizontalPosition: 'end',
		width: 290
	}, config ) );

	this.colorPicker = new HighlightColorPickerWidget( controller );

	this.colorPicker.connect( this, { chooseColor: 'onChooseColor' } );

	this.$body.append( this.colorPicker.$element );
};

/* Initialization */

OO.inheritClass( HighlightPopupWidget, OO.ui.PopupWidget );

/* Methods */

/**
 * Set the button (or other widget) that this popup should hang off.
 *
 * @param {OO.ui.Widget} widget Widget the popup should orient itself to
 */
HighlightPopupWidget.prototype.setAssociatedButton = function ( widget ) {
	this.setFloatableContainer( widget.$element );
	this.$autoCloseIgnore = widget.$element;
};

/**
 * Set the filter item that this popup should control the highlight color for.
 *
 * @param {mw.rcfilters.dm.FilterItem} item
 */
HighlightPopupWidget.prototype.setFilterItem = function ( item ) {
	this.colorPicker.setFilterItem( item );
};

/**
 * When the user chooses a color in the color picker, close the popup.
 */
HighlightPopupWidget.prototype.onChooseColor = function () {
	this.toggle( false );
};

module.exports = HighlightPopupWidget;
