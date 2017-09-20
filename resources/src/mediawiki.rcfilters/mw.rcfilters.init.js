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
				rcTopSection,
				$watchlistDetails,
				wlTopSection,
				savedQueriesPreferenceName = mw.config.get( 'wgStructuredChangeFiltersSavedQueriesPreferenceName' ),
				filtersModel = new mw.rcfilters.dm.FiltersViewModel(),
				changesListModel = new mw.rcfilters.dm.ChangesListViewModel(),
				savedQueriesModel = new mw.rcfilters.dm.SavedQueriesModel(),
				controller = new mw.rcfilters.Controller(
					filtersModel, changesListModel, savedQueriesModel,
					{
						savedQueriesPreferenceName: savedQueriesPreferenceName
					}
				),
				$overlay = $( '<div>' )
					.addClass( 'mw-rcfilters-ui-overlay' ),
				filtersWidget = new mw.rcfilters.ui.FilterWrapperWidget(
					controller, filtersModel, savedQueriesModel, changesListModel, { $overlay: $overlay } ),
				savedLinksListWidget = new mw.rcfilters.ui.SavedLinksListWidget(
					controller, savedQueriesModel, { $overlay: $overlay }
				),
				currentPage = mw.config.get( 'wgCanonicalNamespace' ) +
					':' +
					mw.config.get( 'wgCanonicalSpecialPageName' );

			// TODO: The changesListWrapperWidget should be able to initialize
			// after the model is ready.
			// eslint-disable-next-line no-new
			new mw.rcfilters.ui.ChangesListWrapperWidget(
				filtersModel, changesListModel, controller, $( '.mw-changeslist, .mw-changeslist-empty' ) );

			// Remove the -loading class that may have been added on the server side.
			// If we are in fact going to load a default saved query, this .initialize()
			// call will do that and add the -loading class right back.
			$( 'body' ).removeClass( 'mw-rcfilters-ui-loading' );

			controller.initialize(
				mw.config.get( 'wgStructuredChangeFilters' ),
				mw.config.get( 'wgFormattedNamespaces' ),
				mw.config.get( 'wgRCFiltersChangeTags' )
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

			if ( currentPage === 'Special:Recentchanges' ||
				currentPage === 'Special:Recentchangeslinked' ) {
				$topLinks = $( '.mw-recentchanges-toplinks' ).detach();

				rcTopSection = new mw.rcfilters.ui.RcTopSectionWidget(
					savedLinksListWidget, $topLinks
				);
				filtersWidget.setTopSection( rcTopSection.$element );
			} // end Special:RC

			if ( currentPage === 'Special:Watchlist' ) {
				$( '#contentSub, form#mw-watchlist-resetbutton' ).detach();
				$watchlistDetails = $( '.watchlistDetails' ).detach().contents();

				wlTopSection = new mw.rcfilters.ui.WatchlistTopSectionWidget(
					controller, changesListModel, savedLinksListWidget, $watchlistDetails
				);
				filtersWidget.setTopSection( wlTopSection.$element );
			} // end Special:WL

			/**
			 * Fired when initialization of the filtering interface for changes list is complete.
			 *
			 * @event structuredChangeFilters_ui_initialized
			 * @member mw.hook
			 */
			mw.hook( 'structuredChangeFilters.ui.initialized' ).fire();
		}
	};

	$( rcfilters.init );

	module.exports = rcfilters;

}( mediaWiki, jQuery ) );
