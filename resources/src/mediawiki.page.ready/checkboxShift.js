/**
 * Enable checkboxes to be checked or unchecked in a row by clicking one,
 * holding shift and clicking another one.
 *
 * @method checkboxShift
 * @memberof module:mediawiki.page.ready
 * @param {jQuery} $box
 */
module.exports = function ( $box ) {
	let prev;
	// When our boxes are clicked..
	$box.on( 'click', ( e ) => {
		// And one has been clicked before...
		if ( prev && e.shiftKey ) {
			// Check or uncheck this one and all in-between checkboxes,
			// except for disabled ones
			$box
				.slice(
					Math.min( $box.index( prev ), $box.index( e.target ) ),
					Math.max( $box.index( prev ), $box.index( e.target ) ) + 1
				)
				.filter( function () {
					return !this.disabled && this.checked !== e.target.checked;
				} )
				.prop( 'checked', e.target.checked )
				// Since the state change is a consequence of direct user action,
				// fire the 'change' event (see T313077).
				.trigger( 'change' );
		}
		// Either way, remember this as the last clicked one
		prev = e.target;
	} );
};
