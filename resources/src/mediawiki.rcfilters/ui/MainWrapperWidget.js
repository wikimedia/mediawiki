( function () {
	var SavedLinksListWidget = require( './SavedLinksListWidget.js' ),
		FilterWrapperWidget = require( './FilterWrapperWidget.js' ),
		ChangesListWrapperWidget = require( './ChangesListWrapperWidget.js' ),
		RcTopSectionWidget = require( './RcTopSectionWidget.js' ),
		RclTopSectionWidget = require( './RclTopSectionWidget.js' ),
		WatchlistTopSectionWidget = require( './WatchlistTopSectionWidget.js' ),
		FormWrapperWidget = require( './FormWrapperWidget.js' ),
		MainWrapperWidget;

	/**
	 * Wrapper for changes list content
	 *
	 * @class mw.rcfilters.ui.MainWrapperWidget
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller Controller
	 * @param {mw.rcfilters.dm.FiltersViewModel} model View model
	 * @param {mw.rcfilters.dm.SavedQueriesModel} savedQueriesModel Saved queries model
	 * @param {mw.rcfilters.dm.ChangesListViewModel} changesListModel
	 * @param {Object} config Configuration object
	 * @cfg {jQuery} $topSection Top section container
	 * @cfg {jQuery} $filtersContainer
	 * @cfg {jQuery} $changesListContainer
	 * @cfg {jQuery} $formContainer
	 * @cfg {boolean} [collapsed] Filter area is collapsed
	 * @cfg {jQuery} [$wrapper] A jQuery object for the wrapper of the general
	 *  system. If not given, falls back to this widget's $element
	 */
	MainWrapperWidget = function MwRcfiltersUiMainWrapperWidget(
		controller, model, savedQueriesModel, changesListModel, config
	) {
		config = $.extend( {}, config );

		// Parent
		MainWrapperWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;
		this.changesListModel = changesListModel;
		this.$topSection = config.$topSection;
		this.$filtersContainer = config.$filtersContainer;
		this.$changesListContainer = config.$changesListContainer;
		this.$formContainer = config.$formContainer;
		this.$overlay = $( '<div>' ).addClass( 'mw-rcfilters-ui-overlay' );
		this.$wrapper = config.$wrapper || this.$element;

		this.savedLinksListWidget = new SavedLinksListWidget(
			controller, savedQueriesModel, { $overlay: this.$overlay }
		);

		this.filtersWidget = new FilterWrapperWidget(
			controller,
			model,
			savedQueriesModel,
			changesListModel,
			{
				$overlay: this.$overlay,
				$wrapper: this.$wrapper,
				collapsed: config.collapsed
			}
		);

		this.changesListWidget = new ChangesListWrapperWidget(
			model, changesListModel, controller, this.$changesListContainer );

		/* Events */

		// Toggle changes list overlay when filters menu opens/closes. We use overlay on changes list
		// to prevent users from accidentally clicking on links in results, while menu is opened.
		// Overlay on changes list is not the same as this.$overlay
		this.filtersWidget.connect( this, { menuToggle: this.onFilterMenuToggle.bind( this ) } );

		// Initialize
		this.$filtersContainer.append( this.filtersWidget.$element );
		$( 'body' )
			.append( this.$overlay )
			.addClass( 'mw-rcfilters-ui-initialized' );
	};

	/* Initialization */

	OO.inheritClass( MainWrapperWidget, OO.ui.Widget );

	/* Methods */

	/**
	 * Set the content of the top section, depending on the type of special page.
	 *
	 * @param {string} specialPage
	 */
	MainWrapperWidget.prototype.setTopSection = function ( specialPage ) {
		var topSection;

		if ( specialPage === 'Recentchanges' ) {
			topSection = new RcTopSectionWidget(
				this.savedLinksListWidget, this.$topSection
			);
			this.filtersWidget.setTopSection( topSection.$element );
		}

		if ( specialPage === 'Recentchangeslinked' ) {
			topSection = new RclTopSectionWidget(
				this.savedLinksListWidget, this.controller,
				this.model.getGroup( 'toOrFrom' ).getItemByParamName( 'showlinkedto' ),
				this.model.getGroup( 'page' ).getItemByParamName( 'target' )
			);

			this.filtersWidget.setTopSection( topSection.$element );
		}

		if ( specialPage === 'Watchlist' ) {
			topSection = new WatchlistTopSectionWidget(
				this.controller, this.changesListModel, this.savedLinksListWidget, this.$topSection
			);

			this.filtersWidget.setTopSection( topSection.$element );
		}
	};

	/**
	 * Filter menu toggle event listener
	 *
	 * @param {boolean} isVisible
	 */
	MainWrapperWidget.prototype.onFilterMenuToggle = function ( isVisible ) {
		this.changesListWidget.toggleOverlay( isVisible );
	};

	/**
	 * Initialize FormWrapperWidget
	 *
	 * @return {mw.rcfilters.ui.FormWrapperWidget} Form wrapper widget
	 */
	MainWrapperWidget.prototype.initFormWidget = function () {
		return new FormWrapperWidget(
			this.model, this.changesListModel, this.controller, this.$formContainer );
	};

	module.exports = MainWrapperWidget;
}() );
