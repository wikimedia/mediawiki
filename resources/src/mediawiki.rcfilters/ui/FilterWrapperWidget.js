( function () {
	var FilterTagMultiselectWidget = require( './FilterTagMultiselectWidget.js' ),
		LiveUpdateButtonWidget = require( './LiveUpdateButtonWidget.js' ),
		ChangesLimitAndDateButtonWidget = require( './ChangesLimitAndDateButtonWidget.js' ),
		FilterWrapperWidget;

	/**
	 * List displaying all filter groups
	 *
	 * @class mw.rcfilters.ui.FilterWrapperWidget
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
	 * @cfg {jQuery} [$wrapper] A jQuery object for the wrapper of the general
	 *  system. If not given, falls back to this widget's $element
	 * @cfg {boolean} [collapsed] Filter area is collapsed
	 */
	FilterWrapperWidget = function MwRcfiltersUiFilterWrapperWidget(
		controller, model, savedQueriesModel, changesListModel, config
	) {
		var $bottom;
		config = config || {};

		// Parent
		FilterWrapperWidget.parent.call( this, config );
		// Mixin constructors
		OO.ui.mixin.PendingElement.call( this, config );

		this.controller = controller;
		this.model = model;
		this.queriesModel = savedQueriesModel;
		this.changesListModel = changesListModel;
		this.$overlay = config.$overlay || this.$element;
		this.$wrapper = config.$wrapper || this.$element;

		this.filterTagWidget = new FilterTagMultiselectWidget(
			this.controller,
			this.model,
			this.queriesModel,
			{
				$overlay: this.$overlay,
				collapsed: config.collapsed,
				$wrapper: this.$wrapper
			}
		);

		this.liveUpdateButton = new LiveUpdateButtonWidget(
			this.controller,
			this.changesListModel
		);

		this.numChangesAndDateWidget = new ChangesLimitAndDateButtonWidget(
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

		if ( this.controller.pollingRate ) {
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

	OO.inheritClass( FilterWrapperWidget, OO.ui.Widget );
	OO.mixinClass( FilterWrapperWidget, OO.ui.mixin.PendingElement );

	/* Methods */

	/**
	 * Set the content of the top section
	 *
	 * @param {jQuery} $topSectionElement
	 */
	FilterWrapperWidget.prototype.setTopSection = function ( $topSectionElement ) {
		this.$top.append( $topSectionElement );
	};

	/**
	 * Respond to the user clicking the 'show new changes' button
	 */
	FilterWrapperWidget.prototype.onShowNewChangesClick = function () {
		this.controller.showNewChanges();
	};

	/**
	 * Respond to changes list model newChangesExist
	 *
	 * @param {boolean} newChangesExist Whether new changes exist
	 */
	FilterWrapperWidget.prototype.onNewChangesExist = function ( newChangesExist ) {
		this.showNewChangesLink.toggle( newChangesExist );
	};

	module.exports = FilterWrapperWidget;
}() );
