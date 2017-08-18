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
				filtersModel = new mw.rcfilters.dm.FiltersViewModel(),
				changesListModel = new mw.rcfilters.dm.ChangesListViewModel(),
				savedQueriesModel = new mw.rcfilters.dm.SavedQueriesModel( {}, filtersModel ),
				controller = new mw.rcfilters.Controller( filtersModel, changesListModel, savedQueriesModel ),
				$overlay = $( '<div>' )
					.addClass( 'mw-rcfilters-ui-overlay' ),
				filtersWidget = new mw.rcfilters.ui.FilterWrapperWidget(
					controller, filtersModel, savedQueriesModel, changesListModel, { $overlay: $overlay } );

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
				filtersModel, changesListModel, controller, $( 'fieldset.rcoptions' ) );

			$( '.rcfilters-container' ).append( filtersWidget.$element );
			$( 'body' ).append( $overlay );
			$( '.rcfilters-head' ).addClass( 'mw-rcfilters-ui-ready' );

			$( 'a.mw-helplink' ).attr(
				'href',
				'https://www.mediawiki.org/wiki/Special:MyLanguage/Help:New_filters_for_edit_review'
			);

			controller.replaceUrl();

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
		}
	};

	$( rcfilters.init );

	module.exports = rcfilters;

}( mediaWiki, jQuery ) );
