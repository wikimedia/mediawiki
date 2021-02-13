/*!
 * Scripts for WatchlistExpiry on action=edit
 */
'use strict';

// Toggle the watchlist-expiry dropdown's disabled state according to the
// selected state of the watchthis checkbox.
$( function () {
	var watchThisWidget, expiryWidget,
		// The 'wpWatchthis' and 'wpWatchlistExpiry' fields come from EditPage.php.
		watchThisNode = document.getElementById( 'wpWatchthisWidget' ),
		expiryNode = document.getElementById( 'wpWatchlistExpiryWidget' );

	if ( watchThisNode && expiryNode ) {
		watchThisWidget = OO.ui.infuse( watchThisNode );
		expiryWidget = OO.ui.infuse( expiryNode );
		// Set initial state to match the watchthis checkbox.
		expiryWidget.setDisabled( !watchThisWidget.isSelected() );

		// Change state on every change of the watchthis checkbox.
		watchThisWidget.on( 'change', function ( enabled ) {
			expiryWidget.setDisabled( !enabled );
		} );
	}
} );
