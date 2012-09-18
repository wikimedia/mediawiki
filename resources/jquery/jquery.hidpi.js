/**
 * Responsive images based on data-src-* attributes.
 *
 * Recognizes data-src-1-5 and data-src-2-0 for 1.5 and 2.0 device pixel ratios.
 * Call on a document or part of a document to replace image srcs in that section.
 *
 */
( function ( $ ) {

/**
 * Implement responsive images based on data-src-* attributes.
 *
 * @method
 * @returns {jQuery} This selection
 */
$.fn.hidpi = function ( options ) {
	var $target = this;

	// @todo add support for dpi media query checks on Firefox, IE
	var devicePixelRatio = window.devicePixelRatio || 1;

	if ( devicePixelRatio > 1 ) {
		var srcKey;
		if ( devicePixelRatio >= 2 ) {
			srcKey = 'src-2-0';
		} else {
			srcKey = 'src-1-5';
		}
		$target.find( 'img' ).each( function() {
			var $img = $( this ),
				src = $img.data( srcKey );
			if ( src ) {
				$img.attr( 'src', src );
			}
		});
	}

	return $target;
};

}( jQuery ) );
