( function () {
	var ItemMenuOptionWidget = require( './ItemMenuOptionWidget.js' ),
		FilterMenuOptionWidget;

	/**
	 * A widget representing a single toggle filter
	 *
	 * @class mw.rcfilters.ui.FilterMenuOptionWidget
	 * @extends mw.rcfilters.ui.ItemMenuOptionWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} filtersViewModel
	 * @param {mw.rcfilters.dm.FilterItem} invertModel
	 * @param {mw.rcfilters.dm.FilterItem} itemModel Filter item model
	 * @param {mw.rcfilters.ui.HighlightPopupWidget} highlightPopup Shared highlight color picker popup
	 * @param {Object} config Configuration object
	 */
	FilterMenuOptionWidget = function MwRcfiltersUiFilterMenuOptionWidget(
		controller, filtersViewModel, invertModel, itemModel, highlightPopup, config
	) {
		config = config || {};

		this.controller = controller;
		this.invertModel = invertModel;
		this.model = itemModel;

		// Parent
		FilterMenuOptionWidget.parent.call( this, controller, filtersViewModel, this.invertModel, itemModel, highlightPopup, config );

		// Event
		this.model.getGroupModel().connect( this, { update: 'onGroupModelUpdate' } );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterMenuOptionWidget' );
	};

	/* Initialization */
	OO.inheritClass( FilterMenuOptionWidget, ItemMenuOptionWidget );

	/* Static properties */

	// We do our own scrolling to top
	FilterMenuOptionWidget.static.scrollIntoViewOnSelect = false;

	/* Methods */

	/**
	 * @inheritdoc
	 */
	FilterMenuOptionWidget.prototype.updateUiBasedOnState = function () {
		// Parent
		FilterMenuOptionWidget.parent.prototype.updateUiBasedOnState.call( this );

		this.setCurrentMuteState();
	};

	/**
	 * Respond to item group model update event
	 */
	FilterMenuOptionWidget.prototype.onGroupModelUpdate = function () {
		this.setCurrentMuteState();
	};

	/**
	 * Set the current muted view of the widget based on its state
	 */
	FilterMenuOptionWidget.prototype.setCurrentMuteState = function () {
		if (
			this.model.getGroupModel().getView() === 'namespaces' &&
			this.invertModel.isSelected()
		) {
			// This is an inverted behavior than the other rules, specifically
			// for inverted namespaces
			this.setFlags( {
				muted: this.model.isSelected()
			} );
		} else {
			this.setFlags( {
				muted: (
					this.model.isConflicted() ||
					(
						// Item is also muted when any of the items in its group is active
						this.model.getGroupModel().isActive() &&
						// But it isn't selected
						!this.model.isSelected() &&
						// And also not included
						!this.model.isIncluded()
					)
				)
			} );
		}
	};

	module.exports = FilterMenuOptionWidget;
}() );
