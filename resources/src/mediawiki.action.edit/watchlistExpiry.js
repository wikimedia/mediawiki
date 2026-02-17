/*!
 * Scripts for WatchlistExpiry and WatchlistLabels on action=edit
 */
'use strict';

// Toggle the watchlist-expiry dropdown's and watchlist-labels widget's disabled
// state according to the selected state of the watchthis checkbox.
$( () => {
	// The 'wpWatchthis', 'wpWatchlistExpiry', and 'wpWatchlistLabels' fields
	// come from EditPage.php.
	const watchThisNode = document.getElementById( 'wpWatchthisWidget' ),
		expiryNode = document.getElementById( 'wpWatchlistExpiryWidget' ),
		labelsNode = document.getElementById( 'wpWatchlistLabelsWidget' );

	if ( !watchThisNode ) {
		return;
	}

	const watchThisWidget = OO.ui.infuse( watchThisNode );

	if ( expiryNode ) {
		const expiryWidget = OO.ui.infuse( expiryNode );
		expiryWidget.setDisabled( !watchThisWidget.isSelected() );
		watchThisWidget.on( 'change', ( enabled ) => {
			expiryWidget.setDisabled( !enabled );
		} );
	}

	if ( labelsNode ) {
		// The labels widget JS class is in a separate RL module that may not
		// be loaded yet; wait for it before infusing.
		mw.loader.using( 'mediawiki.widgets.MenuTagMultiselectWidget' ).then( () => {
			const labelsWidget = OO.ui.infuse( labelsNode );
			labelsWidget.setDisabled( !watchThisWidget.isSelected() );
			watchThisWidget.on( 'change', ( enabled ) => {
				labelsWidget.setDisabled( !enabled );
			} );
		} );
	}
} );
