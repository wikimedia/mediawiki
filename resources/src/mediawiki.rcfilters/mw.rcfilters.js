/**
 * @class
 * @singleton
 */
mw.rcfilters = {
	Controller: require( './Controller.js' ),
	HighlightColors: require( './HighlightColors.js' ),
	UriProcessor: require( './UriProcessor.js' ),
	dm: {
		ChangesListViewModel: require( './dm/ChangesListViewModel.js' ),
		FilterGroup: require( './dm/FilterGroup.js' ),
		FilterItem: require( './dm/FilterItem.js' ),
		FiltersViewModel: require( './dm/FiltersViewModel.js' ),
		ItemModel: require( './dm/ItemModel.js' ),
		SavedQueriesModel: require( './dm/SavedQueriesModel.js' ),
		SavedQueryItemModel: require( './dm/SavedQueryItemModel.js' )
	},
	ui: {
		MainWrapperWidget: require( './ui/MainWrapperWidget.js' )
	},
	utils: {
		addArrayElementsUnique: function ( arr, elements ) {
			elements = Array.isArray( elements ) ? elements : [ elements ];

			elements.forEach( function ( element ) {
				if ( arr.indexOf( element ) === -1 ) {
					arr.push( element );
				}
			} );

			return arr;
		},
		normalizeParamOptions: function ( givenOptions, legalOptions ) {
			var result = [];

			if ( givenOptions.indexOf( 'all' ) > -1 ) {
				// If anywhere in the values there's 'all', we
				// treat it as if only 'all' was selected.
				// Example: param=valid1,valid2,all
				// Result: param=all
				return [ 'all' ];
			}

			// Get rid of any dupe and invalid parameter, only output
			// valid ones
			// Example: param=valid1,valid2,invalid1,valid1
			// Result: param=valid1,valid2
			givenOptions.forEach( function ( value ) {
				if (
					legalOptions.indexOf( value ) > -1 &&
					result.indexOf( value ) === -1
				) {
					result.push( value );
				}
			} );

			return result;
		}
	}
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
		filtersModel = new mw.rcfilters.dm.FiltersViewModel(),
		changesListModel = new mw.rcfilters.dm.ChangesListViewModel( $initialFieldset ),
		savedQueriesModel = new mw.rcfilters.dm.SavedQueriesModel( filtersModel ),
		specialPage = mw.config.get( 'wgCanonicalSpecialPageName' ),
		controller = new mw.rcfilters.Controller(
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

	mainWrapperWidget = new mw.rcfilters.ui.MainWrapperWidget(
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
	 * @event structuredChangeFilters_ui_initialized
	 * @member mw.hook
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

module.exports = mw.rcfilters;
