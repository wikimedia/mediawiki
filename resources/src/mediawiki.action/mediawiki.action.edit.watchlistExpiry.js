/*
 * Javascript for the mediawiki.action.edit.watchlistExpiry module.
 */
( function () {
	'use strict';

	/*
	 * Toggle the watchlist-expiry dropdown's disabled state according to the
	 * selected state of the watchthis checkbox.
	 */
	$( function () {
		var watchThisWidget, watchlistExpiryWidget;

		if ( document.getElementById( 'wpWatchthisWidget' ) &&
				document.getElementById( 'wpWatchlistExpiryWidget' ) ) {

			watchThisWidget = OO.ui.infuse( '#wpWatchthisWidget' );
			watchlistExpiryWidget = OO.ui.infuse( '#wpWatchlistExpiryWidget' );
			// Set initial state to match the watchthis checkbox.
			watchlistExpiryWidget.setDisabled( !watchThisWidget.isSelected() );

			// Change state on every change of the watchthis checkbox.
			watchThisWidget.on( 'change', function ( enabled ) {
				watchlistExpiryWidget.setDisabled( !enabled );
			} );
		}
	} );

}() );
