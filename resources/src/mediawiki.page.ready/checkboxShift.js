let prev;

function clickHandler( e ) {
	// When our boxes are clicked and one has been clicked before...
	if ( prev && e.shiftKey ) {
		// Check or uncheck this one and all in-between checkboxes,
		// except for disabled ones
		const $checkboxes = e.data.$checkboxes;
		$checkboxes
			.slice(
				Math.min( $checkboxes.index( prev ), $checkboxes.index( e.target ) ),
				Math.max( $checkboxes.index( prev ), $checkboxes.index( e.target ) ) + 1
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
}

/**
 * Enable checkboxes to be checked or unchecked in a row by clicking one,
 * holding shift and clicking another one.
 *
 * @method checkboxShift
 * @memberof module:mediawiki.page.ready
 * @param {jQuery} $checkboxes
 */
module.exports = function ( $checkboxes ) {
	$checkboxes
		.off( 'click', clickHandler )
		.on( 'click', { $checkboxes: $checkboxes }, clickHandler );
};
