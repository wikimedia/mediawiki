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
 * Implement responsive images based on srcset attributes, if browser has no
 * native srcset support.
 *
 * @method
 * @returns {jQuery} This selection
 */
$.fn.hidpi = function() {
	var $target = this;

	// @todo add support for dpi media query checks on Firefox, IE
	var devicePixelRatio = $.devicePixelRatio();

	if ( devicePixelRatio > 1 ) {
		var foo = new Image();
		if ( typeof foo.srcset === 'undefined' ) {
			// No native srcset support.
			$target.find( 'img' ).each( function() {
				var $img = $( this ),
					srcset = $img.attr( 'srcset' );
				if ( typeof srcset === 'string' && srcset !== '' ) {
					var candidates = srcset.split( / *, */ );
					for ( var i = 0; i < candidates.length; i++ ) {
						var candidate = candidates[i],
							bits = candidate.split( / +/ ),
							src = bits[0];
						if ( bits.length > 1 && bits[1].substr( -1, 1 ) === 'x' ) {
							var ratioStr = bits[1].substr( 0, bits[1].length - 1 ),
								ratio = parseFloat( ratioStr );
							if ( ratio == devicePixelRatio ) {
								// @fixme: check closest match rather than exact match
								// current code assumes we'll see 1.5 or 2 exactly.
								$img.attr( 'src', src );
							}
						}
					}
				}
			});
		}
	}

	return $target;
};

}( jQuery ) );
