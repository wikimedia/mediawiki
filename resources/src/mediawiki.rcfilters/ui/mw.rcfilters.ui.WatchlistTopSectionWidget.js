( function ( mw ) {
	/**
	 * Top section (between page title and filters) on Special:Watchlist
	 *
	 * @extends OO.ui.Widget
	 *
	 * @constructor
	 * @param {mw.rcfilters.Controller} controller
	 * @param {mw.rcfilters.dm.ChangesListViewModel} changesListModel
	 * @param {mw.rcfilters.ui.SavedLinksListWidget} savedLinksListWidget
	 * @param {jQuery} $watchlistDetails
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.WatchlistTopSectionWidget = function MwRcfiltersUiWatchlistTopSectionWidget(
		controller, changesListModel, savedLinksListWidget, $watchlistDetails, config
	) {
		var editWatchlistButton,
			markSeenButton;
		config = config || {};

		// Parent
		mw.rcfilters.ui.WatchlistTopSectionWidget.parent.call( this, config );

		editWatchlistButton = new mw.rcfilters.ui.EditWatchlistButtonWidget();
		markSeenButton = new mw.rcfilters.ui.MarkSeenButtonWidget( controller, changesListModel );

		this.$element
			.addClass( 'mw-rcfilters-ui-watchlistTopSectionWidget' )
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
			)
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-row' )
					.addClass( 'mw-rcfilters-ui-row-separator' ) // todo: make this work
			)
			.append(
				$( '<div>' )
					.addClass( 'mw-rcfilters-ui-row' )
					.append(
						$( '<div>' )
							.addClass( 'mw-rcfilters-ui-cell' )
							.append( markSeenButton.$element )
					)
					.append(
						!mw.user.isAnon() ?
							$( '<div>' )
								.addClass( 'mw-rcfilters-ui-cell' )
								.addClass( 'mw-rcfilters-ui-watchlistTopSectionWidget-savedLinks' )
								.append( savedLinksListWidget.$element ) :
							null
					)
			);
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.WatchlistTopSectionWidget, OO.ui.Widget );
}( mediaWiki ) );
