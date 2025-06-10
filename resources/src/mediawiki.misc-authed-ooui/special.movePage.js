/*!
 * JavaScript for Special:MovePage
 */
( function () {

	if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'Movepage' ) {
		return;
	}

	$( () => {
		const wpReason = OO.ui.infuse( $( '#wpReason' ) );

		// Infuse for pretty dropdown
		OO.ui.infuse( $( '#wpNewTitle' ) );

		const wpReasonList = OO.ui.infuse( $( '#wpReasonList' ).closest( '.oo-ui-widget' ) );

		mw.widgets.visibleCodePointLimitWithDropdown( wpReason, wpReasonList, mw.config.get( 'wgCommentCodePointLimit' ) );

		// Hide or show the "Watchlist expiry" element if the "Watch this page" checkbox is checked
		function infuseIfExists( $el ) {
			if ( !$el.length ) {
				return null;
			}
			return OO.ui.infuse( $el );
		}
		const watch = infuseIfExists( $( '#watch' ) );
		const wpWatchlistExpiry = infuseIfExists( $( '#wpWatchlistExpiry' ) );
		const wpWatchlistExpiryLabel = infuseIfExists( $( '#wpWatchlistExpiryLabel' ) );

		function toggleExpiryVisibility() {
			if ( watch === null || wpWatchlistExpiry === null || wpWatchlistExpiryLabel === null ) {
				// If any of the elements are not present, we don't need to do anything
				return;
			}
			if ( !watch.isSelected() ) {
				wpWatchlistExpiry.$element.hide();
				wpWatchlistExpiryLabel.$element.hide();
			} else {
				wpWatchlistExpiry.$element.show();
				wpWatchlistExpiryLabel.$element.show();
			}
		}
		watch.on( 'change', toggleExpiryVisibility );
		toggleExpiryVisibility();
	} );
}() );
