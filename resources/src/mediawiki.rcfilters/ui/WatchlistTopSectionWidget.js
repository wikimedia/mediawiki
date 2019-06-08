( function () {
	var MarkSeenButtonWidget = require( './MarkSeenButtonWidget.js' ),
		WatchlistTopSectionWidget;
	/**
	 * Top section (between page title and filters) on Special:Watchlist
	 *
	 * @class mw.rcfilters.ui.WatchlistTopSectionWidget
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller
	 * @param {mw.rcfilters.dm.ChangesListViewModel} changesListModel
	 * @param {mw.rcfilters.ui.SavedLinksListWidget} savedLinksListWidget
	 * @param {jQuery} $watchlistDetails Content of the 'details' section that includes watched pages count
	 * @param {Object} [config] Configuration object
	 */
	WatchlistTopSectionWidget = function MwRcfiltersUiWatchlistTopSectionWidget(
		controller, changesListModel, savedLinksListWidget, $watchlistDetails, config
	) {
		var editWatchlistButton,
			markSeenButton,
			$topTable,
			$bottomTable,
			$separator;
		config = config || {};

		// Parent
		WatchlistTopSectionWidget.parent.call( this, config );

		editWatchlistButton = new OO.ui.ButtonWidget( {
			label: mw.msg( 'rcfilters-watchlist-edit-watchlist-button' ),
			icon: 'edit',
			href: require( '../config.json' ).StructuredChangeFiltersEditWatchlistUrl
		} );
		markSeenButton = new MarkSeenButtonWidget( controller, changesListModel );

		$topTable = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-table' )
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-row' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-cell' )
							.addClass( 'mw-rcfilters-ui-watchlistTopSectionWidget-watchlistDetails' )
							.append( $watchlistDetails )
					)
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-cell' )
							.addClass( 'mw-rcfilters-ui-watchlistTopSectionWidget-editWatchlistButton' )
							.append( editWatchlistButton.$element )
					)
			);

		$bottomTable = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-table' )
			.addClass( 'mw-rcfilters-ui-watchlistTopSectionWidget-savedLinksTable' )
			.append(
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
							.addClass( 'mw-rcfilters-ui-watchlistTopSectionWidget-savedLinks' )
							.append( savedLinksListWidget.$element )
					)
			);

		$separator = $( '<div>' )
			.addClass( 'mw-rcfilters-ui-watchlistTopSectionWidget-separator' );

		this.$element
			.addClass( 'mw-rcfilters-ui-watchlistTopSectionWidget' )
			.append( $topTable, $separator, $bottomTable );
	};

	/* Initialization */

	OO.inheritClass( WatchlistTopSectionWidget, OO.ui.Widget );

	module.exports = WatchlistTopSectionWidget;
}() );
