const TagItemWidget = require( './TagItemWidget.js' );

/**
 * Extend OOUI's FilterTagItemWidget to also display a popup on hover.
 *
 * @class mw.rcfilters.ui.FilterTagItemWidget
 * @ignore
 * @extends mw.rcfilters.ui.TagItemWidget
 *
 * @param {mw.rcfilters.Controller} controller
 * @param {mw.rcfilters.dm.FiltersViewModel} filtersViewModel
 * @param {mw.rcfilters.dm.FilterItem} invertModel
 * @param {mw.rcfilters.dm.FilterItem} itemModel Item model
 * @param {Object} config Configuration object
 */
const FilterTagItemWidget = function MwRcfiltersUiFilterTagItemWidget(
	controller, filtersViewModel, invertModel, itemModel, config
) {
	config = config || {};

	FilterTagItemWidget.super.call( this, controller, filtersViewModel, invertModel, itemModel, config );

	this.$element
		.addClass( 'mw-rcfilters-ui-filterTagItemWidget' );
};

/* Initialization */

OO.inheritClass( FilterTagItemWidget, TagItemWidget );

/* Methods */

/**
 * @inheritdoc
 */
FilterTagItemWidget.prototype.setCurrentMuteState = function () {
	this.setFlags( {
		muted: (
			!this.itemModel.isSelected() ||
			this.itemModel.isIncluded() ||
			this.itemModel.isFullyCovered()
		),
		invalid: this.itemModel.isSelected() && this.itemModel.isConflicted()
	} );
};

module.exports = FilterTagItemWidget;
