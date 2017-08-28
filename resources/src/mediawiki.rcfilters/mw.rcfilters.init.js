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
			var toplinksTitle,
				topLinksCookieName = 'rcfilters-toplinks-collapsed-state',
				topLinksCookie = mw.cookie.get( topLinksCookieName ),
				topLinksCookieValue = topLinksCookie || 'collapsed',
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
				savedLinksListWidget  = new mw.rcfilters.ui.SavedLinksListWidget(
					controller, savedQueriesModel, { $overlay: $overlay }
				),
				markSeenButton,
				editWatchlistButton,
				watchlistDetails,
				currentPage = mw.config.get( 'wgCanonicalNamespace' ) +
					':' +
					mw.config.get( 'wgCanonicalSpecialPageName' );

			// TODO: The changesListWrapperWidget should be able to initialize
			// after the model is ready.
			// eslint-disable-next-line no-new
			new mw.rcfilters.ui.ChangesListWrapperWidget(
				filtersModel, changesListModel, controller, $( '.mw-changeslist, .mw-changeslist-empty' ) );

			controller.initialize(
				mw.config.get( 'wgStructuredChangeFilters' ),
				mw.config.get( 'wgFormattedNamespaces' ),
				mw.config.get( 'wgRCFiltersChangeTags' )
			);

			// eslint-disable-next-line no-new
			new mw.rcfilters.ui.FormWrapperWidget(
				filtersModel, changesListModel, controller, $( 'fieldset.cloptions' ) );

			$( '.rcfilters-container' ).append( filtersWidget.$element );
			$( 'body' ).append( $overlay );
			$( '.rcfilters-head' ).addClass( 'mw-rcfilters-ui-ready' );

			$( 'a.mw-helplink' ).attr(
				'href',
				'https://www.mediawiki.org/wiki/Special:MyLanguage/Help:New_filters_for_edit_review'
			);

			controller.replaceUrl();

			if ( currentPage === 'Special:Recentchanges' ) {
				toplinksTitle = new OO.ui.ButtonWidget( {
					framed: false,
					indicator: topLinksCookieValue === 'collapsed' ? 'down' : 'up',
					flags: [ 'progressive' ],
					label: $( '<span>' ).append( mw.message( 'rcfilters-other-review-tools' ).parse() ).contents()
				} );
				$( '.mw-recentchanges-toplinks-title' ).replaceWith( toplinksTitle.$element );
				// Move the top links to a designated area so it's near the
				// 'saved filters' button and make it collapsible
				$( '.mw-recentchanges-toplinks' )
					.addClass( 'mw-rcfilters-ui-ready' )
					.makeCollapsible( {
						collapsed: topLinksCookieValue === 'collapsed',
						$customTogglers: toplinksTitle.$element
					} )
					.on( 'beforeExpand.mw-collapsible', function () {
						mw.cookie.set( topLinksCookieName, 'expanded' );
						toplinksTitle.setIndicator( 'up' );
					} )
					.on( 'beforeCollapse.mw-collapsible', function () {
						mw.cookie.set( topLinksCookieName, 'collapsed' );
						toplinksTitle.setIndicator( 'down' );
					} )
					.appendTo( '.mw-rcfilters-ui-filterWrapperWidget-top-placeholder' );

				filtersWidget.setTopRows(
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-row' )
						.append(
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-filterWrapperWidget-top-placeholder' )
						)
						.append(
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-filterWrapperWidget-top-savedLinks' )
								.append( !mw.user.isAnon() ? savedLinksListWidget.$element : null )
						)
				);
			} // end Special:RC

			if ( currentPage === 'Special:Watchlist' ) {
				$( [
					'#contentSub', // line with username and edit watchlist links
					'.watchlistDetails', // "10 pages on your watchlist..."
					'form#mw-watchlist-resetbutton' // mark all as seen
				].join( ',' ) ).detach();

				watchlistDetails =  mw.message(
					'rcfilters-watchlist-details',
					mw.config.get( 'wgStructuredChangeFiltersWatchlistItemCount' )
				).parse();

				editWatchlistButton = new mw.rcfilters.ui.EditWatchlistButtonWidget();
				markSeenButton = new mw.rcfilters.ui.MarkSeenButtonWidget( controller, changesListModel );

				filtersWidget.setTopRows( [
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-row' )
						.append(
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-filterWrapperWidget-top-watchlistDetails' )
								.append( watchlistDetails )
						)
						.append(
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-filterWrapperWidget-top-editWatchlistButton' )
								.append( editWatchlistButton.$element )
						),
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-row' )
						.addClass( 'mw-rcfilters-ui-row-separator' ),
					$( '<div>' )
						.addClass( 'mw-rcfilters-ui-row' )
						.append(
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.append( markSeenButton.$element )
						)
						.append(
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-filterWrapperWidget-top-savedLinks' )
								.append( !mw.user.isAnon() ? savedLinksListWidget.$element : null )
						)
				] );
			} // end Special:WL
		}
	};

	$( rcfilters.init );

	module.exports = rcfilters;

}( mediaWiki, jQuery ) );
