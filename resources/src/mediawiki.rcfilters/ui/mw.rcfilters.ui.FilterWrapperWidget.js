( function ( mw ) {
	/**
	 * List displaying all filter groups
	 *
	 * @extends OO.ui.Widget
	 * @mixins OO.ui.mixin.PendingElement
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {mw.rcfilters.dm.SavedQueriesModel} savedQueriesModel Saved queries model
	 * @param {Object} [config] Configuration object
	 * @cfg {Object} [filters] A definition of the filter groups in this list
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.FilterWrapperWidget = function MwRcfiltersUiFilterWrapperWidget( controller, model, savedQueriesModel, config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.FilterWrapperWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.PendingElement.call( this, config );

		this.controller = controller;
		this.model = model;
		this.queriesModel = savedQueriesModel;
		this.$overlay = config.$overlay || this.$element;

		this.filterTagWidget = new mw.rcfilters.ui.FilterTagMultiselectWidget(
			this.controller,
			this.model,
			this.queriesModel,
			{ $overlay: this.$overlay }
		);

		this.namespaceButton = new OO.ui.ButtonWidget( {
			label: mw.msg( 'namespaces' ),
			icon: 'article',
			classes: [ 'mw-rcfilters-ui-filterWrapperWidget-namespaceToggle' ]
		} );
		this.namespaceButton.setActive( this.model.getCurrentView() === 'namespaces' );

		// Events
		this.model.connect( this, { update: 'onModelUpdate' } );
		this.namespaceButton.connect( this, { click: 'onNamespaceToggleClick' } );

		// Initialize
		this.$element
			.addClass( 'mw-rcfilters-ui-filterWrapperWidget' );

		if ( mw.config.get( 'wgStructuredChangeFiltersEnableSaving' ) ) {
			this.savedLinksListWidget = new mw.rcfilters.ui.SavedLinksListWidget(
				this.controller,
				this.queriesModel,
				{ $overlay: this.$overlay }
			);

			this.$element.append(
				this.savedLinksListWidget.$element
			);

		}

		this.$element.append(
			this.filterTagWidget.$element,
			this.namespaceButton.$element
		);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.mixin.PendingElement );

	/* Methods */

	/**
	 * Respond to model update event
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onModelUpdate = function () {
		// Synchronize the state of the toggle button with the current view
		this.namespaceButton.setActive( this.model.getCurrentView() === 'namespaces' );
	};

	/**
	 * Respond to namespace toggle button click
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onNamespaceToggleClick = function () {
		this.controller.switchView( 'namespaces' );
		this.filterTagWidget.focus();
	};
}( mediaWiki ) );
