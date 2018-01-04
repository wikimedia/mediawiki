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
		this.changesListModel = changesListModel;
		this.$overlay = config.$overlay || this.$element;

		this.filterTagWidget = new mw.rcfilters.ui.FilterTagMultiselectWidget(
			this.controller,
			this.model,
			this.queriesModel,
			{ $overlay: this.$overlay }
		);

		this.liveUpdateButton = new mw.rcfilters.ui.LiveUpdateButtonWidget(
			this.controller,
			this.changesListModel
		);

		this.numChangesAndDateWidget = new mw.rcfilters.ui.ChangesLimitAndDateButtonWidget(
			this.controller,
			this.model,
			{
				$overlay: this.$overlay
			}
		);

		this.showNewChangesLink = new OO.ui.ButtonWidget( {
			icon: 'reload',
			framed: false,
			label: mw.msg( 'rcfilters-show-new-changes' ),
			flags: [ 'progressive' ],
			classes: [ 'mw-rcfilters-ui-filterWrapperWidget-showNewChanges' ]
		} );

		// Events
		this.filterTagWidget.menu.connect( this, { toggle: [ 'emit', 'menuToggle' ] } );
		this.changesListModel.connect( this, { newChangesExist: 'onNewChangesExist' } );
		this.showNewChangesLink.connect( this, { click: 'onShowNewChangesClick' } );
		this.showNewChangesLink.toggle( false );

		// Initialize
		this.$top = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-filterWrapperWidget-top' );

		$bottom = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-filterWrapperWidget-bottom' )
			.append(
				this.showNewChangesLink.$element,
				this.numChangesAndDateWidget.$element
			);

		if ( mw.config.get( 'StructuredChangeFiltersLiveUpdatePollingRate' ) ) {
			$bottom.prepend( this.liveUpdateButton.$element );
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

	/**
	 * Respond to the user clicking the 'show new changes' button
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onShowNewChangesClick = function () {
		this.controller.showNewChanges();
	};

	/**
	 * Respond to changes list model newChangesExist
	 *
	 * @param {boolean} newChangesExist Whether new changes exist
	 */
	mw.rcfilters.ui.FilterWrapperWidget.prototype.onNewChangesExist = function ( newChangesExist ) {
		this.showNewChangesLink.toggle( newChangesExist );
	};
}( mediaWiki ) );
