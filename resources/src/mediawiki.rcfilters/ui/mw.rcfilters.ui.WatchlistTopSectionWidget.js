( function () {
	/**
	 * Top section (between page title and filters) on Special:Watchlist
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller
	 * @param {mw.rcfilters.dm.ChangesListViewModel} changesListModel
	 * @param {mw.rcfilters.ui.SavedLinksListWidget} savedLinksListWidget
	 * @param {jQuery} $watchlistDetails Content of the 'details' section that includes watched pages count
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.WatchlistTopSectionWidget = function MwRcfiltersUiWatchlistTopSectionWidget(
		controller, changesListModel, savedLinksListWidget, $watchlistDetails, config
	) {
		var editWatchlistButton,
			markSeenButton,
			$topTable,
			$bottomTable,
			$separator;
		config = config || {};

		// Parent
		mw.rcfilters.ui.WatchlistTopSectionWidget.parent.call( this, config );

		editWatchlistButton = new OO.ui.ButtonWidget( {
			label: mw.msg( 'rcfilters-watchlist-edit-watchlist-button' ),
			icon: 'edit',
			href: mw.config.get( 'wgStructuredChangeFiltersEditWatchlistUrl' )
		} );
		markSeenButton = new mw.rcfilters.ui.MarkSeenButtonWidget( controller, changesListModel );

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

	OO.inheritClass( mw.rcfilters.ui.WatchlistTopSectionWidget, OO.ui.Widget );
}() );
