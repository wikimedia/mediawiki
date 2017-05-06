( function ( mw ) {
	/**
	 * A widget representing a single toggle filter
	 *
	 * @extends mw.rcfilters.ui.ItemMenuOptionWidget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller RCFilters controller
	 * @param {mw.rcfilters.dm.NamespaceItem} model Namespace item model
	 * @param {Object} config Configuration object
	 */
	mw.rcfilters.ui.NamespaceMenuOptionWidget = function MwRcfiltersUiNamespaceMenuOptionWidget( controller, model, config ) {
		config = config || {};

		this.controller = controller;
		this.model = model;

		// Parent
		mw.rcfilters.ui.NamespaceMenuOptionWidget.parent.call( this, controller, model, config );

		this.$element
			.addClass( 'mw-rcfilters-ui-namespaceMenuOptionWidget' )
			.addClass(
				'mw-rcfilters-ui-namespaceMenuOptionWidget-type-' + (
					this.model.getNamespaceID() % 2 === 0 ?
					'subject' : 'talk'
				)
			);
	};

	/* Initialization */
	OO.inheritClass( mw.rcfilters.ui.NamespaceMenuOptionWidget, mw.rcfilters.ui.ItemMenuOptionWidget );

	/* Static properties */

	// We do our own scrolling to top
	mw.rcfilters.ui.NamespaceMenuOptionWidget.static.scrollIntoViewOnSelect = false;

	/* Methods */

	/**
	 * @inheritdoc
	 */
	mw.rcfilters.ui.NamespaceMenuOptionWidget.prototype.onModelUpdate = function () {
		// Parent
		mw.rcfilters.ui.NamespaceMenuOptionWidget.parent.prototype.onModelUpdate.call( this );

		this.$element.toggleClass( 'mw-rcfilters-ui-namespaceMenuOptionWidget-inverted', this.model.isInverted() );
	};

}( mediaWiki ) );
