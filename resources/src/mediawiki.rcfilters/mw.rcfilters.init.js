/*!
 * JavaScript for Special:RecentChanges
 */
( function ( mw, $ ) {
	var rcfilters = {
		/**
		 * @member mw.rcfilters
		 * @private
		 */
		init: function () {
			var $topLinks,
				topSection,
				$watchlistDetails,
				namespaces,
				conditionalViews = {},
				savedQueriesPreferenceName = mw.config.get( 'wgStructuredChangeFiltersSavedQueriesPreferenceName' ),
				daysPreferenceName = mw.config.get( 'wgStructuredChangeFiltersDaysPreferenceName' ),
				limitPreferenceName = mw.config.get( 'wgStructuredChangeFiltersLimitPreferenceName' ),
				filtersModel = new mw.rcfilters.dm.FiltersViewModel(),
				changesListModel = new mw.rcfilters.dm.ChangesListViewModel(),
				savedQueriesModel = new mw.rcfilters.dm.SavedQueriesModel( filtersModel ),
				controller = new mw.rcfilters.Controller(
					filtersModel, changesListModel, savedQueriesModel,
					{
						savedQueriesPreferenceName: savedQueriesPreferenceName,
						daysPreferenceName: daysPreferenceName,
						limitPreferenceName: limitPreferenceName
					}
				),
				$overlay = $( '<div>' )
					.addClass( 'mw-rcfilters-ui-overlay' ),
				filtersWidget = new mw.rcfilters.ui.FilterWrapperWidget(
					controller, filtersModel, savedQueriesModel, changesListModel, { $overlay: $overlay } ),
				savedLinksListWidget = new mw.rcfilters.ui.SavedLinksListWidget(
					controller, savedQueriesModel, { $overlay: $overlay }
				),
				specialPage = mw.config.get( 'wgCanonicalSpecialPageName' ),
				$changesListRoot = $( [
					'.mw-changeslist',
					'.mw-changeslist-empty',
					'.mw-changeslist-timeout',
					'.mw-changeslist-notargetpage'
				].join( ', ' ) );

			if ( specialPage === 'Recentchangeslinked' ) {
				conditionalViews.recentChangesLinked = {
					groups: [
						{
							name: 'page',
							type: 'any_value',
							title: '',
							hidden: true,
							isSticky: false,
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
							isSticky: false,
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

			// TODO: The changesListWrapperWidget should be able to initialize
			// after the model is ready.

			// eslint-disable-next-line no-new
			new mw.rcfilters.ui.ChangesListWrapperWidget(
				filtersModel, changesListModel, controller, $changesListRoot );

			// Remove the -loading class that may have been added on the server side.
			// If we are in fact going to load a default saved query, this .initialize()
			// call will do that and add the -loading class right back.
			$( 'body' ).removeClass( 'mw-rcfilters-ui-loading' );

			// Remove Media namespace
			namespaces = mw.config.get( 'wgFormattedNamespaces' );
			delete namespaces[ mw.config.get( 'wgNamespaceIds' ).media ];

			controller.initialize(
				mw.config.get( 'wgStructuredChangeFilters' ),
				namespaces,
				mw.config.get( 'wgRCFiltersChangeTags' ),
				conditionalViews
			);

			// eslint-disable-next-line no-new
			new mw.rcfilters.ui.FormWrapperWidget(
				filtersModel, changesListModel, controller, $( 'fieldset.cloptions' ) );

			$( '.rcfilters-container' ).append( filtersWidget.$element );
			$( 'body' )
				.append( $overlay )
				.addClass( 'mw-rcfilters-ui-initialized' );

			$( 'a.mw-helplink' ).attr(
				'href',
				'https://www.mediawiki.org/wiki/Special:MyLanguage/Help:New_filters_for_edit_review'
			);

			controller.replaceUrl();

			if ( specialPage === 'Recentchanges' ) {
				$topLinks = $( '.mw-recentchanges-toplinks' ).detach();

				topSection = new mw.rcfilters.ui.RcTopSectionWidget(
					savedLinksListWidget, $topLinks
				);
				filtersWidget.setTopSection( topSection.$element );
			} // end Recentchanges

			if ( specialPage === 'Recentchangeslinked' ) {
				topSection = new mw.rcfilters.ui.RclTopSectionWidget(
					savedLinksListWidget, controller,
					filtersModel.getGroup( 'toOrFrom' ).getItemByParamName( 'showlinkedto' ),
					filtersModel.getGroup( 'page' ).getItemByParamName( 'target' )
				);
				filtersWidget.setTopSection( topSection.$element );
			} // end Recentchangeslinked

			if ( specialPage === 'Watchlist' ) {
				$( '#contentSub, form#mw-watchlist-resetbutton' ).detach();
				$watchlistDetails = $( '.watchlistDetails' ).detach().contents();

				topSection = new mw.rcfilters.ui.WatchlistTopSectionWidget(
					controller, changesListModel, savedLinksListWidget, $watchlistDetails
				);
				filtersWidget.setTopSection( topSection.$element );
			} // end Watchlist

			/**
			 * Fired when initialization of the filtering interface for changes list is complete.
			 *
			 * @event structuredChangeFilters_ui_initialized
			 * @member mw.hook
			 */
			mw.hook( 'structuredChangeFilters.ui.initialized' ).fire();
		}
	};

	// Early execute of init
	if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
		rcfilters.init();
	} else {
		$( rcfilters.init );
	}

	module.exports = rcfilters;

}( mediaWiki, jQuery ) );
