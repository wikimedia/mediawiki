( function ( mw ) {
	/**
	 * Namespace widget
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.PendingElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.NamespacesViewModel} model View model
	 * @param {Object} [config] Configuration object
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.NamespaceWrapperWidget = function MwRcfiltersUiNamespaceWrapperWidget( controller, model, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.NamespaceWrapperWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.PendingElement.call( this, config );

		this.controller = controller;
		this.model = model;
		this.$overlay = config.$overlay || this.$element;

		this.namespaceTagWidget = new mw.rcfilters.ui.NamespaceTagMultiselectWidget(
			this.controller,
			this.model,
			{ $overlay: this.$overlay }
		);

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-namespaceWrapperWidget' )
			.append( this.namespaceTagWidget.$element );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.NamespaceWrapperWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.NamespaceWrapperWidget, OO.ui.mixin.PendingElement );
}( mediaWiki ) );
