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

		this.viewToggle = new OO.ui.ButtonSelectWidget( {
			classes: [ 'mw-rcfilters-ui-filterWrapperWidget-viewToggleButtons' ],
			items: [
				new OO.ui.ButtonOptionWidget( {
					data: 'namespaces',
					label: mw.msg( 'namespaces' ),
					icon: 'article',
					classes: [ 'mw-rcfilters-ui-filterWrapperWidget-viewToggleButtons-namespaces' ]
				} ),
				new OO.ui.ButtonOptionWidget( {
					data: 'tags',
					label: mw.msg( 'rcfilters-view-tags' ),
					icon: 'tag',
					classes: [ 'mw-rcfilters-ui-filterWrapperWidget-viewToggleButtons-tags' ]
				} )
			]
		} );

		// Events
		this.model.connect( this, { update: 'onModelUpdate' } );
		this.viewToggle.connect( this, { select: 'onViewToggleSelect' } );

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
			this.viewToggle.$element
		);
		this.viewToggle.toggle( !!mw.config.get( 'wgStructuredChangeFiltersEnableExperimentalViews' ) );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.mixin.PendingElement );

	/* Methods */

	/**
	 * Respond to model update event
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onModelUpdate = function () {
		// Synchronize the state of the toggle buttons with the current view
		this.viewToggle.selectItemByData( this.model.getCurrentView() );
	};

	/**
	 * Respond to namespace toggle button click
	 *
	 * @param {OO.ui.ButtonWidget} buttonWidget The button that was clicked
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onViewToggleSelect = function ( buttonWidget ) {
		if ( buttonWidget ) {
			this.controller.switchView( buttonWidget.getData() );
			this.filterTagWidget.focus();
		}
	};

}( mediaWiki ) );
