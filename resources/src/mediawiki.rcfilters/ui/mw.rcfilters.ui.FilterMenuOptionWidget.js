( function ( mw ) {
	/**
	 * A widget representing a single toggle filter
	 *
	 * @extends mw.rcfilters.ui.ItemMenuOptionWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {mw.rcfilters.dm.FilterItem} model Filter item model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FilterMenuOptionWidget = function MwRcfiltersUiFilterMenuOptionWidget( controller, model, config ) {
		config = config || {};

		this.controller = controller;
		this.model = model;

		// Parent
		mw.rcfilters.ui.FilterMenuOptionWidget.parent.call( this, controller, model, config );

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
	 * Respond to item group model update event
	 */
	mw.rcfilters.ui.FilterMenuOptionWidget.prototype.onGroupModelUpdate = function () {
		this.setCurrentMuteState();
	};

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterMenuOptionWidget.prototype.setCurrentMuteState = function () {
		// Parent
		mw.rcfilters.ui.FilterMenuOptionWidget.parent.prototype.setCurrentMuteState.call( this );

		this.$element.toggleClass(
			'mw-rcfilters-ui-filterMenuOptionWidget-muted',
			this.model.isConflicted() ||
			(
				// Item is also muted when any of the items in its group is active
				this.model.getGroupModel().isActive() &&
				// But it isn't selected
				!this.model.isSelected() &&
				// And also not included
				!this.model.isIncluded()
			)
		);
	};
}( mediaWiki ) );
