( function ( mw ) {
	/**
	 * Extend OOUI's FilterTagItemWidget to also display a popup on hover.
	 *
	 * @class
	 * @extends mw.rcfilters.ui.TagItemWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller
	 * @param {mw.rcfilters.dm.FilterItem} model Item model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.FilterTagItemWidget = function MwRcfiltersUiFilterTagItemWidget( controller, model, config ) {
		config = config || {};

		mw.rcfilters.ui.FilterTagItemWidget.parent.call( this, controller, model, config );

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
				!this.model.isSelected() ||
				this.model.isIncluded() ||
				this.model.isFullyCovered()
			),
			invalid: this.model.isSelected() && this.model.isConflicted()
		} );
	};
}( mediaWiki, jQuery ) );
