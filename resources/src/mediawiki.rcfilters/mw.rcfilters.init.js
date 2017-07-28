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
			var topLinksCookieName = 'rcfilters-toplinks-collapsed-state',
				topLinksCookie = mw.cookie.get( topLinksCookieName ),
				topLinksCookieValue = topLinksCookie ? topLinksCookie : 'collapsed',
				filtersModel = new mw.rcfilters.dm.FiltersViewModel(),
				changesListModel = new mw.rcfilters.dm.ChangesListViewModel(),
				savedQueriesModel = new mw.rcfilters.dm.SavedQueriesModel(),
				controller = new mw.rcfilters.Controller( filtersModel, changesListModel, savedQueriesModel ),
				$overlay = $( '<div>' )
					.addClass( 'mw-rcfilters-ui-overlay' ),
				filtersWidget = new mw.rcfilters.ui.FilterWrapperWidget(
					controller, filtersModel, savedQueriesModel, { $overlay: $overlay } );

			// TODO: The changesListWrapperWidget should be able to initialize
			// after the model is ready.
			// eslint-disable-next-line no-new
			new mw.rcfilters.ui.ChangesListWrapperWidget(
				filtersModel, changesListModel, $( '.mw-changeslist, .mw-changeslist-empty' ) );

			controller.initialize(
				mw.config.get( 'wgStructuredChangeFilters' ),
				mw.config.get( 'wgFormattedNamespaces' ),
				mw.config.get( 'wgRCFiltersChangeTags' )
			);

			// eslint-disable-next-line no-new
			new mw.rcfilters.ui.FormWrapperWidget(
				filtersModel, changesListModel, controller, $( 'fieldset.rcoptions' ) );

			$( '.rcfilters-container' ).append( filtersWidget.$element );
			$( 'body' ).append( $overlay );
			$( '.rcfilters-head' ).addClass( 'mw-rcfilters-ui-ready' );

			$( 'a.mw-helplink' ).attr(
				'href',
				'https://www.mediawiki.org/wiki/Special:MyLanguage/Help:New_filters_for_edit_review'
			);

			controller.replaceUrl();

			$( '.mw-recentchanges-toplinks' )
				.addClass( 'mw-rcfilters-ui-ready' )
				.makeCollapsible( {
					collapsed: topLinksCookieValue === 'collapsed'
				} )
				.on( 'beforeExpand.mw-collapsible', function () {
					mw.cookie.set( topLinksCookieName, 'expanded' );
				} )
				.on( 'beforeCollapse.mw-collapsible', function () {
					mw.cookie.set( topLinksCookieName, 'collapsed' );
				} );

		}
	};

	$( rcfilters.init );

	module.exports = rcfilters;

}( mediaWiki, jQuery ) );
