/**
 * Responsive images based on data-src-* attributes.
 *
 * Recognizes data-src-1-5 and data-src-2-0 for 1.5 and 2.0 device pixel ratios.
 * Call on a document or part of a document to replace image srcs in that section.
 *
 */
( function ( $ ) {

/**
 * Detect reported or approximate device pixel ratio.
 * 1.0 means 1 CSS pixel is 1 hardware pixel
 * 2.0 means 1 CSS pixel is 2 hardware pixels
 * etc
 *
 * Uses window.devicePixelRatio if available, or CSS media queries on IE.
 *
 * @method
 * @returns {number} Device pixel ratio
 */
$.devicePixelRatio = function() {
	if (typeof window.devicePixelRatio !== 'undefined') {
		return window.devicePixelRatio;
	} else if (typeof window.msMatchMedia !== 'undefined') {
		// IE 9, 10 don't report pixel ratio directly, but we can get the
		// screen DPI and divide by 96. We'll bracket to [1, 1.5, 2.0] for
		// simplicity, but you may get different values depending on zoom
		// factor, size of screen and orientation in Metro IE.
		if (window.msMatchMedia('(min-resolution: 192dpi)').matches ) {
			return 2;
		} else if (window.msMatchMedia('(min-resolution: 144dpi)').matches ) {
			return 1.5;
		} else {
			return 1;
		};
	} else {
		// Assume 1 if unknown.
		return 1;
	}
};

/**
 * Implement responsive images based on data-src-* attributes.
 *
 * @method
 * @returns {jQuery} This selection
 */
$.fn.hidpi = function() {
	var $target = this;

	// @todo add support for dpi media query checks on Firefox, IE
	var devicePixelRatio = $.devicePixelRatio();
	console.log(devicePixelRatio);

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
