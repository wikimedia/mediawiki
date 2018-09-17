/*!
 * JavaScript for Special:RecentChanges
 */
( function () {
	var rcfilters = {
		/**
		 * @member mw.rcfilters
		 * @private
		 */
		init: function () {
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
				$( '#contentSub, form#mw-watchlist-resetbutton' ).remove();
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
									'default': ''
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
									'default': false
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
					$wrapper: $( 'body' ),
					$topSection: $topSection,
					$filtersContainer: $( '.rcfilters-container' ),
					$changesListContainer: $( '.mw-changeslist, .mw-changeslist-empty' ),
					$formContainer: $initialFieldset,
					collapsed: initialCollapsedState
				}
			);

			// Remove the -loading class that may have been added on the server side.
			// If we are in fact going to load a default saved query, this .initialize()
			// call will do that and add the -loading class right back.
			$( 'body' ).removeClass( 'mw-rcfilters-ui-loading' );

			controller.initialize(
				mw.config.get( 'wgStructuredChangeFilters' ),
				// All namespaces without Media namespace
				rcfilters.getNamespaces( [ 'Media' ] ),
				mw.config.get( 'wgRCFiltersChangeTags' ),
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
		},

		/**
		 * Get list of namespaces and remove unused ones
		 *
		 * @member mw.rcfilters
		 * @private
		 *
		 * @param {Array} unusedNamespaces Names of namespaces to remove
		 * @return {Array} Filtered array of namespaces
		 */
		getNamespaces: function ( unusedNamespaces ) {
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
	};

	// Import i18n messages from config
	mw.messages.set( mw.config.get( 'wgStructuredChangeFiltersMessages' ) );

	// Early execute of init
	if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
		rcfilters.init();
	} else {
		$( rcfilters.init );
	}

	module.exports = rcfilters;

}() );
