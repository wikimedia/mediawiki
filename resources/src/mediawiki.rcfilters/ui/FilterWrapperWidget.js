const FilterTagMultiselectWidget = require( './FilterTagMultiselectWidget.js' ),
	LiveUpdateButtonWidget = require( './LiveUpdateButtonWidget.js' ),
	ChangesLimitAndDateButtonWidget = require( './ChangesLimitAndDateButtonWidget.js' );

/**
 * List displaying all filter groups.
 *
 * @class mw.rcfilters.ui.FilterWrapperWidget
 * @ignore
 * @extends OO.ui.Widget
 * @mixes OO.ui.mixin.PendingElement
 *
 * @param {mw.rcfilters.Controller} controller Controller
 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
 * @param {mw.rcfilters.dm.SavedQueriesModel} savedQueriesModel Saved queries model
 * @param {mw.rcfilters.dm.ChangesListViewModel} changesListModel
 * @param {Object} [config] Configuration object
 * @param {Object} [config.filters] A definition of the filter groups in this list
 * @param {jQuery} [config.$overlay] A jQuery object serving as overlay for popups
 * @param {jQuery} [config.$wrapper] A jQuery object for the wrapper of the general
 *  system. If not given, falls back to this widget's $element
 * @param {boolean} [config.collapsed] Filter area is collapsed
 */
const FilterWrapperWidget = function MwRcfiltersUiFilterWrapperWidget(
	controller, model, savedQueriesModel, changesListModel, config
) {
	config = config || {};

	// Parent
	FilterWrapperWidget.super.call( this, config );
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
			$wrapper: this.$wrapper,
			isMobile: OO.ui.isMobile()
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
			classes: [ 'mw-rcfilters-ui-filterWrapperWidget-numChangesAndDateWidget' ],
			$overlay: this.$overlay
		}
	);

	this.showNewChangesLink = new OO.ui.ButtonWidget( {
		icon: 'reload',
		framed: false,
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

	const $bottom = $( '<div>' )
		.addClass( 'mw-rcfilters-ui-filterWrapperWidget-bottom' )
		.addClass( OO.ui.isMobile() ? 'mw-rcfilters-ui-filterWrapperWidget-bottom-mobile' : '' )
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
	if ( newChangesExist ) {
		this.showNewChangesLink.setLabel(
			mw.msg(
				'rcfilters-show-new-changes',
				this.changesListModel.getNextFromFormatted()
			)
		);
	}
	this.showNewChangesLink.toggle( newChangesExist );
};

module.exports = FilterWrapperWidget;
