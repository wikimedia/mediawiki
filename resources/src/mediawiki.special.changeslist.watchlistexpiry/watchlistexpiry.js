( function () {

	/**
	 * On touch devices, make it possible to click on the watchlist expiry clock icon
	 * and get a text display of the remaining days or hours for that watchlist item.
	 *
	 * @private
	 * @param {Event} event The click event.
	 */
	function addDaysLeftMessages( event ) {
		const $clock = $( event.target );
		const timeLeft = $clock.data( 'days-left' );
		if ( timeLeft === undefined ) {
			// Give up if there's no data attribute (e.g. in the watchlist legend).
			return;
		}
		const msg = timeLeft > 0 ?
			mw.msg( 'watchlist-expiry-days-left', timeLeft ) :
			mw.msg( 'watchlist-expiry-hours-left' );
		$clock.after( $( '<span>' )
			.addClass( 'mw-watchlistexpiry-msg' )
			.text( mw.msg( 'parentheses', msg ) ) );
	}

	$( () => {
		if ( 'ontouchstart' in document.documentElement ) {
			$( '.mw-changesList-watchlistExpiry' ).one( 'click', addDaysLeftMessages );
		}
	} );

}() );
