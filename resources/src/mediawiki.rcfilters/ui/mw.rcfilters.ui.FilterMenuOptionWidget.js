( function ( mw ) {
	/**
	 * A widget representing a single toggle filter
	 *
	 * @extends mw.rcfilters.ui.ItemMenuOptionWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} filtersViewModel
	 * @param {mw.rcfilters.dm.FilterItem} invertModel
	 * @param {mw.rcfilters.dm.FilterItem} itemModel Filter item model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FilterMenuOptionWidget = function MwRcfiltersUiFilterMenuOptionWidget(
		controller, filtersViewModel, invertModel, itemModel, config
	) {
		config = config || {};

		this.controller = controller;
		this.invertModel = invertModel;
		this.model = itemModel;

		// Parent
		mw.rcfilters.ui.FilterMenuOptionWidget.parent.call( this, controller, filtersViewModel, this.invertModel, itemModel, config );

		// Event
		this.model.getGroupModel().connect( this, { update: 'onGroupModelUpdate' } );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterMenuOptionWidget' );
	};

	/* Initialization */
	OO.inheritClass( mw.rcfilters.ui.FilterMenuOptionWidget, mw.rcfilters.ui.ItemMenuOptionWidget );

	/* Static properties */

	// We do our own scrolling to top
	mw.rcfilters.ui.FilterMenuOptionWidget.static.scrollIntoViewOnSelect = false;

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterMenuOptionWidget.prototype.updateUiBasedOnState = function () {
		// Parent
		mw.rcfilters.ui.FilterMenuOptionWidget.parent.prototype.updateUiBasedOnState.call( this );

		this.setCurrentMuteState();
	};

	/**
	 * Respond to item group model update event
	 */
	mw.rcfilters.ui.FilterMenuOptionWidget.prototype.onGroupModelUpdate = function () {
		this.setCurrentMuteState();
	};

	/**
	 * Set the current muted view of the widget based on its state
	 */
	mw.rcfilters.ui.FilterMenuOptionWidget.prototype.setCurrentMuteState = function () {
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
}( mediaWiki ) );
