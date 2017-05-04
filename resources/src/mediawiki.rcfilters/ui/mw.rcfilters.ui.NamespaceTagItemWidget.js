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
	mw.rcfilters.ui.NamespaceTagItemWidget = function MwRcfiltersUiNamespaceTagItemWidget( controller, model, config ) {
		config = config || {};

		mw.rcfilters.ui.NamespaceTagItemWidget.parent.call( this, controller, model, config );

		this.controller = controller;
		this.model = model;

		// Event
		this.model.connect( this, { update: 'updateLabel' } );

		// Initialize
		this.updateLabel();
		this.$element
			.addClass( 'mw-rcfilters-ui-namespaceTagItemWidget' );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.NamespaceTagItemWidget, mw.rcfilters.ui.TagItemWidget );

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.NamespaceTagItemWidget.prototype.updateLabel = function () {
		this.setLabel(
			this.model.isInverted() ?
				mw.message( 'rcfilters-namespace-prefix-inverted', this.model.getLabel() ).parse() :
				mw.msg( 'rcfilters-namespace-prefix-regular', this.model.getLabel() )
		);
	};
}( mediaWiki, jQuery ) );
