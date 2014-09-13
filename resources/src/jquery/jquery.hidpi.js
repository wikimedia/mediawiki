/**
 * Responsive images based on 'srcset' and 'window.devicePixelRatio' emulation where needed.
 *
 * Call $().hidpi() on a document or part of a document to replace image srcs in that section.
 *
 * $.devicePixelRatio() can be used to supplement window.devicePixelRatio with support on
 * some additional browsers.
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
 * @return {number} Device pixel ratio
 */
$.devicePixelRatio = function () {
	if ( window.devicePixelRatio !== undefined ) {
		// Most web browsers:
		// * WebKit (Safari, Chrome, Android browser, etc)
		// * Opera
		// * Firefox 18+
		return window.devicePixelRatio;
	} else if ( window.msMatchMedia !== undefined ) {
		// Windows 8 desktops / tablets, probably Windows Phone 8
		//
		// IE 10 doesn't report pixel ratio directly, but we can get the
		// screen DPI and divide by 96. We'll bracket to [1, 1.5, 2.0] for
		// simplicity, but you may get different values depending on zoom
		// factor, size of screen and orientation in Metro IE.
		if ( window.msMatchMedia( '(min-resolution: 192dpi)' ).matches ) {
			return 2;
		} else if ( window.msMatchMedia( '(min-resolution: 144dpi)' ).matches ) {
			return 1.5;
		} else {
			return 1;
		}
	} else {
		// Legacy browsers...
		// Assume 1 if unknown.
		return 1;
	}
};

/**
 * Implement responsive images based on srcset attributes, if browser has no
 * native srcset support.
 *
 * @return {jQuery} This selection
 */
$.fn.hidpi = function () {
	var $target = this,
		// @todo add support for dpi media query checks on Firefox, IE
		devicePixelRatio = $.devicePixelRatio(),
		testImage = new Image();

	if ( devicePixelRatio > 1 && testImage.srcset === undefined ) {
		// No native srcset support.
		$target.find( 'img' ).each( function () {
			var $img = $( this ),
				srcset = $img.attr( 'srcset' ),
				match;
			if ( typeof srcset === 'string' && srcset !== '' ) {
				match = $.matchSrcSet( devicePixelRatio, srcset );
				if (match !== null ) {
					$img.attr( 'src', match );
				}
			}
		});
	}

	return $target;
};

/**
 * Match a srcset entry for the given device pixel ratio
 *
 * Exposed for testing.
 *
 * @param {number} devicePixelRatio
 * @param {string} srcset
 * @return {mixed} null or the matching src string
 */
$.matchSrcSet = function ( devicePixelRatio, srcset ) {
	var candidates,
		candidate,
		bits,
		src,
		i,
		ratioStr,
		ratio,
		selectedRatio = 1,
		selectedSrc = null;
	candidates = srcset.split( / *, */ );
	for ( i = 0; i < candidates.length; i++ ) {
		candidate = candidates[i];
		bits = candidate.split( / +/ );
		src = bits[0];
		if ( bits.length > 1 && bits[1].charAt( bits[1].length - 1 ) === 'x' ) {
			ratioStr = bits[1].substr( 0, bits[1].length - 1 );
			ratio = parseFloat( ratioStr );
			if ( ratio <= devicePixelRatio && ratio > selectedRatio ) {
				selectedRatio = ratio;
				selectedSrc = src;
			}
		}
	}
	return selectedSrc;
};

}( jQuery ) );
