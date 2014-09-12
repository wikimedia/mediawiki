/**
 * jQuery checkboxShiftClick
 *
 * This will enable checkboxes to be checked or unchecked in a row by clicking one,
 * holding shift and clicking another one.
 *
 * @author Timo Tijhof, 2011 - 2012
 * @license GPL v2
 */
( function ( $ ) {
	$.fn.checkboxShiftClick = function () {
		var prevCheckbox = null,
			$box = this;
		// When our boxes are clicked..
		$box.click( function ( e ) {
			// And one has been clicked before...
			if ( prevCheckbox !== null && e.shiftKey ) {
				// Check or uncheck this one and all in-between checkboxes,
				// except for disabled ones
				$box
					.slice(
						Math.min( $box.index( prevCheckbox ), $box.index( e.target ) ),
						Math.max( $box.index( prevCheckbox ), $box.index( e.target ) ) + 1
					)
					.filter( function () {
						return !this.disabled;
					} )
					.prop( 'checked', !!e.target.checked );
			}
			// Either way, update the prevCheckbox variable to the one clicked now
			prevCheckbox = e.target;
		} );
		return $box;
	};
}( jQuery ) );
