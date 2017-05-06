( function ( mw ) {
	/**
	 * Widget representing a namespace in the RCFilters tag area
	 *
	 * @class
	 * @extends mw.rcfilters.ui.TagItemWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller
	 * @param {mw.rcfilters.dm.NamespaceItem} model Item model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.NamespaceTagItemWidget = function MwRcfiltersUiNamespaceTagItemWidget( controller, model, config ) {
		config = config || {};

		mw.rcfilters.ui.NamespaceTagItemWidget.parent.call( this, controller, model, config );

		this.$element
			.addClass( 'mw-rcfilters-ui-namespaceTagItemWidget' );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.NamespaceTagItemWidget, mw.rcfilters.ui.TagItemWidget );

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.NamespaceTagItemWidget.prototype.onModelUpdate = function () {
		// Parent
		mw.rcfilters.ui.TagItemWidget.parent.prototype.onModelUpdate.call( this );

		// Update label
		this.setLabel(
			this.model.isInverted() ?
				mw.msg( 'rcfilters-namespace-prefix-inverted', this.model.getLabel() ) :
				mw.msg( 'rcfilters-namespace-prefix', this.model.getLabel() )
		);
	};
}( mediaWiki, jQuery ) );
