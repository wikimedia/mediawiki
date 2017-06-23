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
		var $bottom;
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

		this.liveUpdateButton = new mw.rcfilters.ui.LiveUpdateButtonWidget(
			this.controller
		);

		this.numChangesWidget = new mw.rcfilters.ui.ChangesLimitButtonWidget(
			this.controller,
			this.model,
			{
				$overlay: this.$overlay
			}
		);

		this.dateWidget = new mw.rcfilters.ui.DateButtonWidget(
			this.controller,
			this.model,
			{
				$overlay: this.$overlay
			}
		);

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

		$bottom = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-filterWrapperWidget-bottom' )
			.append(
				this.numChangesWidget.$element,
				this.dateWidget.$element
			);

		if ( mw.config.get( 'wgStructuredChangeFiltersEnableLiveUpdate' ) ) {
			$bottom.append( this.liveUpdateButton.$element );
		}

		this.$element.append(
			this.filterTagWidget.$element,
			$bottom
		);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.mixin.PendingElement );
}( mediaWiki ) );
