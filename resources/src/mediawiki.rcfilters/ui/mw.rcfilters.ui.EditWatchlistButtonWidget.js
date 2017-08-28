( function ( mw ) {
	/**
	 * Button to go to Special:EditWatchlist
	 *
	 * @extends OO.ui.ButtonWidget
	 *
	 * @constructor
	 * @param {Object} [config] Configuration object
	 */
	mw.rcfilters.ui.EditWatchlistButtonWidget = function MwRcfiltersUiEditWatchlistButtonWidget( config ) {
		config = config || {};

		// Parent
		mw.rcfilters.ui.EditWatchlistButtonWidget.parent.call( this, $.extend( {
			label: mw.message( 'rcfilters-watchlist-editWatchlist-button' ).text(),
			icon: 'edit',
			href: mw.config.get('wgStructuredChangeFiltersEditWatchlistUrl')
		}, config ) );

		this.$element.addClass( 'mw-rcfilters-ui-editWatchlistButtonWidget' );
	};

	/* Initialization */

	OO.inheritClass( mw.rcfilters.ui.EditWatchlistButtonWidget, OO.ui.ButtonWidget );
}( mediaWiki ) );
