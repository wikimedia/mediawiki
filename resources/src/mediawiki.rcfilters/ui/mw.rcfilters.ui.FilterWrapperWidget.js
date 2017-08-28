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
	 * @param {mw.rcfilters.dm.ChangesListViewModel} changesListModel
	 * @param {Object} [config] Configuration object
	 * @cfg {Object} [filters] A definition of the filter groups in this list
	 * @cfg {jQuery} [$overlay] A jQuery object serving as overlay for popups
	 */
	mw.rcfilters.ui.FilterWrapperWidget = function MwRcfiltersUiFilterWrapperWidget(
		controller, model, savedQueriesModel, changesListModel, config
	) {
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
			this.controller,
			changesListModel
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
		this.$top = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-filterWrapperWidget-top' );

		$bottom = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-filterWrapperWidget-bottom' )
			.append(
				this.numChangesWidget.$element,
				this.dateWidget.$element
			);

		if ( mw.rcfilters.featureFlags.liveUpdate ) {
			$bottom.append( this.liveUpdateButton.$element );
		}

		this.$element
			.addClass( 'mw-rcfilters-ui-filterWrapperWidget' )
			.append(
				this.$top,
				this.filterTagWidget.$element,
				$bottom
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.Widget );
	OO.mixinClass( mw.rcfilters.ui.FilterWrapperWidget, OO.ui.mixin.PendingElement );

	/* Methods */

	/**
	 * Set the content of the top section
	 *
	 * @param {jQuery} $topSectionElement
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.setTopSection = function ( $topSectionElement ) {
		this.$top.append( $topSectionElement );
	};
}( mediaWiki ) );
