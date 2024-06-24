var HighlightColors = require( '../HighlightColors.js' );

/**
 * A button to configure highlight for a filter item.
 *
 * @class mw.rcfilters.ui.FilterItemHighlightButton
 * @ignore
 * @extends OO.ui.PopupButtonWidget
 *
 * @param {mw.rcfilters.Controller} controller RCFilters controller
 * @param {mw.rcfilters.dm.FilterItem} model Filter item model
 * @param {mw.rcfilters.ui.HighlightPopupWidget} highlightPopup Shared highlight color picker
 * @param {Object} [config] Configuration object
 */
var FilterItemHighlightButton = function MwRcfiltersUiFilterItemHighlightButton( controller, model, highlightPopup, config ) {
	config = config || {};

	// Parent
	FilterItemHighlightButton.super.call( this, $.extend( true, {}, config, {
		icon: 'highlight',
		indicator: 'down'
	} ) );

	this.controller = controller;
	this.model = model;
	this.popup = highlightPopup;

	// Event
	this.model.connect( this, { update: 'updateUiBasedOnModel' } );
	// This lives inside a MenuOptionWidget, which intercepts mousedown
	// to select the item. We want to prevent that when we click the highlight
	// button
	this.$element.on( 'mousedown', ( e ) => {
		e.stopPropagation();
	} );

	this.updateUiBasedOnModel();

	this.$element
		.addClass( 'mw-rcfilters-ui-filterItemHighlightButton' );
};

/* Initialization */

OO.inheritClass( FilterItemHighlightButton, OO.ui.PopupButtonWidget );

/* Static Properties */

/**
 * @static
 */
FilterItemHighlightButton.static.cancelButtonMouseDownEvents = true;

/* Methods */

FilterItemHighlightButton.prototype.onAction = function () {
	this.popup.setAssociatedButton( this );
	this.popup.setFilterItem( this.model );

	// Parent method
	FilterItemHighlightButton.super.prototype.onAction.call( this );
};

/**
 * Respond to item model update event
 */
FilterItemHighlightButton.prototype.updateUiBasedOnModel = function () {
	var currentColor = this.model.getHighlightColor(),
		widget = this;

	this.$icon.toggleClass(
		'mw-rcfilters-ui-filterItemHighlightButton-circle',
		currentColor !== null
	);

	let hasFilter = false;
	HighlightColors.forEach( ( c ) => {
		hasFilter = hasFilter || c === currentColor;
		// The following classes are used here:
		// * mw-rcfilters-ui-filterItemHighlightButton-circle-color-c1
		// * mw-rcfilters-ui-filterItemHighlightButton-circle-color-c2
		// * mw-rcfilters-ui-filterItemHighlightButton-circle-color-c3
		// * mw-rcfilters-ui-filterItemHighlightButton-circle-color-c4
		// * mw-rcfilters-ui-filterItemHighlightButton-circle-color-c5
		widget.$icon
			.toggleClass(
				'mw-rcfilters-ui-filterItemHighlightButton-circle-color-' + c,
				c === currentColor
			);
	} );
	this.$icon.toggleClass( 'mw-no-invert', hasFilter );
};

module.exports = FilterItemHighlightButton;
