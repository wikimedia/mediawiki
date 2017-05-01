/*!
 * JavaScript for Special:RecentChanges
 */
( function ( mw, $ ) {
	/**
	 * @class mw.rcfilters
	 * @singleton
	 */
	var rcfilters = {
		/** */
		init: function () {
			var filtersModel = new mw.rcfilters.dm.FiltersViewModel(),
				changesListModel = new mw.rcfilters.dm.ChangesListViewModel(),
				controller = new mw.rcfilters.Controller( filtersModel, changesListModel ),
				$overlay = $( '<div>' )
					.addClass( 'mw-rcfilters-ui-overlay' ),
				filtersWidget = new mw.rcfilters.ui.FilterWrapperWidget(
					controller, filtersModel, { $overlay: $overlay } );

			// TODO: The changesListWrapperWidget should be able to initialize
			// after the model is ready.
			// eslint-disable-next-line no-new
			new mw.rcfilters.ui.ChangesListWrapperWidget(
				filtersModel, changesListModel, $( '.mw-changeslist, .mw-changeslist-empty' ) );

			controller.initialize( mw.config.get( 'wgStructuredChangeFilters' ) );

			// eslint-disable-next-line no-new
			new mw.rcfilters.ui.FormWrapperWidget(
				filtersModel, changesListModel, controller, $( 'fieldset.rcoptions' ) );

			$( '.rcfilters-container' ).append( filtersWidget.$element );
			$( 'body' ).append( $overlay );

			// Set as ready
			$( '.rcfilters-head' ).addClass( 'mw-rcfilters-ui-ready' );

			window.addEventListener( 'popstate', function () {
				controller.updateStateBasedOnUrl();
				controller.updateChangesList();
			} );

			$( 'a.mw-helplink' ).attr(
				'href',
				'https://www.mediawiki.org/wiki/Special:MyLanguage/Help:New_filters_for_edit_review'
			);

			controller.replaceUrl();
		}
	};

	$( rcfilters.init );

	module.exports = rcfilters;

}( mediaWiki, jQuery ) );
