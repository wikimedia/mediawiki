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
	const $editWatchListButtonIcon = $( '<span>' ).append(
		new OO.ui.IconWidget( { icon: 'edit', classes: [ 'mw-rcfilters-ui-watchlistTopSectionWidget-editWatchlistButtonIcon' ] } ).$element,
		mw.msg( 'rcfilters-watchlist-edit-watchlist-button' )
	);
	const $editSettingsIcon = $( '<span>' ).append(
		new OO.ui.IconWidget( { icon: 'settings', classes: [ 'mw-rcfilters-ui-watchlistTopSectionWidget-editWatchlistButtonIcon' ] } ).$element,
		mw.msg( 'rcfilters-watchlist-edit-watchlist-preferences-button' )
	);
	const $editWatchlistButtonLink = $( '<a>' ).attr( 'href',
		require( '../config.json' ).StructuredChangeFiltersEditWatchlistUrl )
		.attr( 'class', 'cdx-docs-link' ).html( $editWatchListButtonIcon );

	const $editWatchlistSettingsButtonLink = $( '<a>' ).attr( 'href',
		mw.util.getUrl( 'Special:Preferences#mw-prefsection-watchlist' ) )
		.attr( 'class', 'cdx-docs-link' ).html( $editSettingsIcon );

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
						.addClass( 'mw-rcfilters-ui-watchlistTopSectionWidget-buttonsSection' )
						.append(
							// eslint-disable-next-line mediawiki/class-doc
							$( '<div>' )
								.addClass(
									// Do not add class in vector-2022 because it is redundant
									mw.config.get( 'skin' ) !== 'vector-2022' && mw.config.get( 'skin' ) !== 'minerva' ?
										'mw-rcfilters-ui-watchlistTopSectionWidget-editWatchlistButton' : undefined
								)
								.append(
									// Do not append edit watchlist button in vector-2022 because it is redundant
									mw.config.get( 'skin' ) !== 'vector-2022' && mw.config.get( 'skin' ) !== 'minerva' ?
										$editWatchlistButtonLink : undefined
								)
						)
						.append(
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-watchlistTopSectionWidget-editWatchlistButton' )
								.append( $editWatchlistSettingsButtonLink )
						)
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
