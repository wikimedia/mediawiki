const MarkSeenButtonWidget = require( './MarkSeenButtonWidget.js' );
/**
 * Top section (between page title and filters) on Special:Watchlist.
 *
 * @class mw.rcfilters.ui.WatchlistTopSectionWidget
 * @ignore
 * @extends OO.ui.Widget
 *
 * @param {mw.rcfilters.Controller} controller
 * @param {mw.rcfilters.dm.ChangesListViewModel} changesListModel
 * @param {mw.rcfilters.ui.SavedLinksListWidget} savedLinksListWidget
 * @param {jQuery} $watchlistDetails Content of the 'details' section that includes watched pages count
 * @param {Object} [config] Configuration object
 */
const WatchlistTopSectionWidget = function MwRcfiltersUiWatchlistTopSectionWidget(
	controller, changesListModel, savedLinksListWidget, $watchlistDetails, config
) {
	config = config || {};

	// Parent
	WatchlistTopSectionWidget.super.call( this, config );

	const editWatchlistButton = new OO.ui.ButtonWidget( {
		label: mw.msg( 'rcfilters-watchlist-edit-watchlist-button' ),
		icon: 'edit',
		href: require( '../config.json' ).StructuredChangeFiltersEditWatchlistUrl
	} );
	const markSeenButton = new MarkSeenButtonWidget( controller, changesListModel );

	const $topTable = $( '<div>' )
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

	const $bottomTable = $( '<div>' )
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

	const $separator = $( '<div>' )
		.addClass( 'mw-rcfilters-ui-watchlistTopSectionWidget-separator' );

	this.$element
		.addClass( 'mw-rcfilters-ui-watchlistTopSectionWidget' )
		.append( $topTable, $separator, $bottomTable );
};

/* Initialization */

OO.inheritClass( WatchlistTopSectionWidget, OO.ui.Widget );

module.exports = WatchlistTopSectionWidget;
