( function ( mw ) {
	/**
	 * Extend OOUI's FilterTagItemWidget to also display a popup on hover.
	 *
	 * @class
	 * @extends mw.rcfilters.ui.TagItemWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} filtersViewModel
	 * @param {mw.rcfilters.dm.FilterItem} invertModel
	 * @param {mw.rcfilters.dm.FilterItem} filterModel Item model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FilterTagItemWidget = function MwRcfiltersUiFilterTagItemWidget(
		controller, filtersViewModel, invertModel, filterModel, config
	) {
		config = config || {};

		mw.rcfilters.ui.FilterTagItemWidget.parent.call( this, controller, filtersViewModel, invertModel, filterModel, config );

		this.$element
			.addClass( 'mw-rcfilters-ui-filterTagItemWidget' );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterTagItemWidget, mw.rcfilters.ui.TagItemWidget );

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.FilterTagItemWidget.prototype.setCurrentMuteState = function () {
		this.setFlags( {
			muted: (
				!this.filterModel.isSelected() ||
				this.filterModel.isIncluded() ||
				this.filterModel.isFullyCovered()
			),
			invalid: this.filterModel.isSelected() && this.filterModel.isConflicted()
		} );
	};
}( mediaWiki, jQuery ) );
