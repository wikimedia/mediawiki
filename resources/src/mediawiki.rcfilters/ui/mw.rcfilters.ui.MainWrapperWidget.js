( function () {
	/**
	 * Wrapper for changes list content
	 *
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
	mw.rcfilters.ui.MainWrapperWidget = function MwRcfiltersUiMainWrapperWidget(
		controller, model, savedQueriesModel, changesListModel, config
	) {
		config = $.extend( {}, config );

		// Parent
		mw.rcfilters.ui.MainWrapperWidget.parent.call( this, config );

		this.controller = controller;
		this.model = model;
		this.changesListModel = changesListModel;
		this.$topSection = config.$topSection;
		this.$filtersContainer = config.$filtersContainer;
		this.$changesListContainer = config.$changesListContainer;
		this.$formContainer = config.$formContainer;
		this.$overlay = $( '<div>' ).addClass( 'mw-rcfilters-ui-overlay' );
		this.$wrapper = config.$wrapper || this.$element;

		this.savedLinksListWidget = new mw.rcfilters.ui.SavedLinksListWidget(
			controller, savedQueriesModel, { $overlay: this.$overlay }
		);

		this.filtersWidget = new mw.rcfilters.ui.FilterWrapperWidget(
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

		this.changesListWidget = new mw.rcfilters.ui.ChangesListWrapperWidget(
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

	OO.inheritClass( mw.rcfilters.ui.MainWrapperWidget, OO.ui.Widget );

	/* Methods */

	/**
	 * Set the content of the top section, depending on the type of special page.
	 *
	 * @param {string} specialPage
	 */
	mw.rcfilters.ui.MainWrapperWidget.prototype.setTopSection = function ( specialPage ) {
		var topSection;

		if ( specialPage === 'Recentchanges' ) {
			topSection = new mw.rcfilters.ui.RcTopSectionWidget(
				this.savedLinksListWidget, this.$topSection
			);
			this.filtersWidget.setTopSection( topSection.$element );
		}

		if ( specialPage === 'Recentchangeslinked' ) {
			topSection = new mw.rcfilters.ui.RclTopSectionWidget(
				this.savedLinksListWidget, this.controller,
				this.model.getGroup( 'toOrFrom' ).getItemByParamName( 'showlinkedto' ),
				this.model.getGroup( 'page' ).getItemByParamName( 'target' )
			);

			this.filtersWidget.setTopSection( topSection.$element );
		}

		if ( specialPage === 'Watchlist' ) {
			topSection = new mw.rcfilters.ui.WatchlistTopSectionWidget(
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
	mw.rcfilters.ui.MainWrapperWidget.prototype.onFilterMenuToggle = function ( isVisible ) {
		this.changesListWidget.toggleOverlay( isVisible );
	};

	/**
	 * Initialize FormWrapperWidget
	 *
	 * @return {mw.rcfilters.ui.FormWrapperWidget} Form wrapper widget
	 */
	mw.rcfilters.ui.MainWrapperWidget.prototype.initFormWidget = function () {
		return new mw.rcfilters.ui.FormWrapperWidget(
			this.model, this.changesListModel, this.controller, this.$formContainer );
	};
}() );
