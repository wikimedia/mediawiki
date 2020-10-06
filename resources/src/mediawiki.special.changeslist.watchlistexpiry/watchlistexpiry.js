( function () {

	/*
	 * On touch devices, make it possible to click on the watchlist expiry clock icon
	 * and get a text display of the remaining days or hours for that watchlist item.
	 *
	 * @private
	 * @param {Event} event The click event.
	 */
	function addDaysLeftMessages( event ) {
		var timeLeft, msg, $label,
			$clock = $( event.target );
		timeLeft = $clock.data( 'days-left' );
		if ( timeLeft === undefined ) {
			// Give up if there's no data attribute (e.g. in the watchlist legend).
			return;
		}
		msg = timeLeft > 0 ?
			mw.msg( 'watchlist-expiry-days-left', timeLeft ) :
			mw.msg( 'watchlist-expiry-hours-left' );
		$label = $( '<span>' );
		$label.addClass( 'mw-watchlistexpiry-msg' );
		$label.text( mw.msg( 'parentheses', msg ) );
		$clock.after( $label );
	}

	$( function () {
		if ( 'ontouchstart' in document.documentElement ) {
			$( '.mw-changesList-watchlistExpiry' ).one( 'click', addDaysLeftMessages );
		}
	} );

}() );
