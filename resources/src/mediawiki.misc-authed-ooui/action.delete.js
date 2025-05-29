/*!
 * JavaScript for action=delete
 */
( function () {
	if ( mw.config.get( 'wgAction' ) !== 'delete' ) {
		return;
	}

	$( () => {
		const reasonList = OO.ui.infuse( $( '#wpDeleteReasonList' ) ),
			reason = OO.ui.infuse( $( '#wpReason' ) );

		mw.widgets.visibleCodePointLimitWithDropdown( reason, reasonList, mw.config.get( 'wgCommentCodePointLimit' ) );
	} );

	// Hide or show the "Watchlist expiry" element if the "Watch this page" checkbox is checked
	function infuseIfExists( $el ) {
		if ( !$el.length ) {
			return null;
		}
		return OO.ui.infuse( $el );
	}
	const watch = infuseIfExists( $( '#wpWatch' ) );
	const wpWatchlistExpiry = infuseIfExists( $( '#wpWatchlistExpiry' ) );

	function toggleExpiryVisibility() {
		if ( watch === null || wpWatchlistExpiry === null ) {
			// If any of the elements are not present, we don't need to do anything
			return;
		}
		if ( !watch.isSelected() ) {
			wpWatchlistExpiry.setDisabled( true );
		} else {
			wpWatchlistExpiry.setDisabled( false );
		}
	}
	watch.on( 'change', toggleExpiryVisibility );
	toggleExpiryVisibility();
}() );
