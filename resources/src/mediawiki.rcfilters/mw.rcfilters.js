/**
 * Components for use on the RecentChanges page. Provided by the `mediawiki.rcfilters.filters.ui` module.
 *
 * @namespace rcfilters
 * @private
 * @singleton
 */
const rcfilters = {
	Controller: require( './Controller.js' ),
	HighlightColors: require( './HighlightColors.js' ),
	UriProcessor: require( './UriProcessor.js' ),
	/**
	 * Models used by RecentChanges Filters.
	 *
	 * @namespace rcfilters.dm
	 * @private
	 */
	dm: {
		ChangesListViewModel: require( './dm/ChangesListViewModel.js' ),
		FilterGroup: require( './dm/FilterGroup.js' ),
		FilterItem: require( './dm/FilterItem.js' ),
		FiltersViewModel: require( './dm/FiltersViewModel.js' ),
		ItemModel: require( './dm/ItemModel.js' ),
		SavedQueriesModel: require( './dm/SavedQueriesModel.js' ),
		SavedQueryItemModel: require( './dm/SavedQueryItemModel.js' )
	},
	/**
	 * Widgets used by RecentChanges Filters.
	 *
	 * @namespace rcfilters.ui
	 * @private
	 */
	ui: {
		MainWrapperWidget: require( './ui/MainWrapperWidget.js' )
	},
	/**
	 * Utils used by RecentChanges Filters.
	 *
	 * @namespace rcfilters.ui
	 * @private
	 */
	utils: require( './utils.js' )
};

/**
 * Get list of namespaces and remove unused ones
 *
 * @private
 *
 * @param {Array} unusedNamespaces Names of namespaces to remove
 * @return {Array} Filtered array of namespaces
 */
function getNamespaces( unusedNamespaces ) {
	var i, length, name, id,
		namespaceIds = mw.config.get( 'wgNamespaceIds' ),
		namespaces = mw.config.get( 'wgFormattedNamespaces' );

	for ( i = 0, length = unusedNamespaces.length; i < length; i++ ) {
		name = unusedNamespaces[ i ];
		id = namespaceIds[ name.toLowerCase() ];
		delete namespaces[ id ];
	}

	return namespaces;
}

/**
 * @private
 */
function init() {
	var $topSection,
		mainWrapperWidget,
		conditionalViews = {},
		$initialFieldset = $( 'fieldset.cloptions' ),
		savedQueriesPreferenceName = mw.config.get( 'wgStructuredChangeFiltersSavedQueriesPreferenceName' ),
		daysPreferenceName = mw.config.get( 'wgStructuredChangeFiltersDaysPreferenceName' ),
		limitPreferenceName = mw.config.get( 'wgStructuredChangeFiltersLimitPreferenceName' ),
		activeFiltersCollapsedName = mw.config.get( 'wgStructuredChangeFiltersCollapsedPreferenceName' ),
		initialCollapsedState = mw.config.get( 'wgStructuredChangeFiltersCollapsedState' ),
		filtersModel = new rcfilters.dm.FiltersViewModel(),
		changesListModel = new rcfilters.dm.ChangesListViewModel( $initialFieldset ),
		savedQueriesModel = new rcfilters.dm.SavedQueriesModel( filtersModel ),
		specialPage = mw.config.get( 'wgCanonicalSpecialPageName' ),
		controller = new rcfilters.Controller(
			filtersModel, changesListModel, savedQueriesModel,
			{
				savedQueriesPreferenceName: savedQueriesPreferenceName,
				daysPreferenceName: daysPreferenceName,
				limitPreferenceName: limitPreferenceName,
				collapsedPreferenceName: activeFiltersCollapsedName,
				normalizeTarget: specialPage === 'Recentchangeslinked'
			}
		);

	// TODO: The changesListWrapperWidget should be able to initialize
	// after the model is ready.

	if ( specialPage === 'Recentchanges' ) {
		$topSection = $( '.mw-recentchanges-toplinks' ).detach();
	} else if ( specialPage === 'Watchlist' ) {
		$( '.mw-watchlist-owner, .mw-watchlist-toollinks, form#mw-watchlist-resetbutton' ).remove();
		$topSection = $( '.watchlistDetails' ).detach().contents();
	} else if ( specialPage === 'Recentchangeslinked' ) {
		conditionalViews.recentChangesLinked = {
			groups: [
				{
					name: 'page',
					type: 'any_value',
					title: '',
					hidden: true,
					sticky: true,
					filters: [
						{
							name: 'target',
							default: ''
						}
					]
				},
				{
					name: 'toOrFrom',
					type: 'boolean',
					title: '',
					hidden: true,
					sticky: true,
					filters: [
						{
							name: 'showlinkedto',
							default: false
						}
					]
				}
			]
		};
	}

	mainWrapperWidget = new rcfilters.ui.MainWrapperWidget(
		controller,
		filtersModel,
		savedQueriesModel,
		changesListModel,
		{
			$wrapper: $( document.body ),
			$topSection: $topSection,
			$filtersContainer: $( '.mw-rcfilters-container' ),
			$changesListContainer: $( '.mw-changeslist, .mw-changeslist-empty' ),
			$formContainer: $initialFieldset,
			collapsed: initialCollapsedState
		}
	);

	// Remove the -loading class that may have been added on the server side.
	// If we are in fact going to load a default saved query, this .initialize()
	// call will do that and add the -loading class right back.
	$( document.body ).removeClass( 'mw-rcfilters-ui-loading' );

	controller.initialize(
		mw.config.get( 'wgStructuredChangeFilters' ),
		// All namespaces without Media namespace
		getNamespaces( [ 'Media' ] ),
		require( './config.json' ).RCFiltersChangeTags,
		conditionalViews
	);

	mainWrapperWidget.initFormWidget( specialPage );

	$( 'a.mw-helplink' ).attr(
		'href',
		'https://www.mediawiki.org/wiki/Special:MyLanguage/Help:New_filters_for_edit_review'
	);

	controller.replaceUrl();

	mainWrapperWidget.setTopSection( specialPage );

	/**
	 * Fired when initialization of the filtering interface for changes list is complete.
	 *
	 * @event ~'structuredChangeFilters.ui.initialized'
	 * @memberof Hooks
	 */
	mw.hook( 'structuredChangeFilters.ui.initialized' ).fire();
}

// Import i18n messages from config
mw.messages.set( mw.config.get( 'wgStructuredChangeFiltersMessages' ) );

// Don't try to run init during QUnit tests, some of the code depends on mw.config variables
// that are not set, and the ui code here isn't even being tested.
if ( !window.QUnit ) {
	if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
		// Early execute of init
		init();
	} else {
		$( init );
	}
}

module.exports = rcfilters;
