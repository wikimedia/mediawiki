/**
 * Responsive images based on `srcset` and `window.devicePixelRatio` emulation where needed.
 *
 * Call `.hidpi()` on a document or part of a document to proces image srcsets within that section.
 *
 * `$.devicePixelRatio()` can be used as a substitute for `window.devicePixelRatio`.
 * It provides a familiar interface to retrieve the pixel ratio for browsers that don't
 * implement `window.devicePixelRatio` but do have a different way of getting it.
 *
 * @class jQuery.plugin.hidpi
 */
( function ( $ ) {

/**
 * Get reported or approximate device pixel ratio.
 *
 * - 1.0 means 1 CSS pixel is 1 hardware pixel
 * - 2.0 means 1 CSS pixel is 2 hardware pixels
 * - etc.
 *
 * Uses `window.devicePixelRatio` if available, or CSS media queries on IE.
 *
 * @static
 * @inheritable
 * @return {number} Device pixel ratio
 */
$.devicePixelRatio = function () {
	if ( window.devicePixelRatio !== undefined ) {
		// Most web browsers:
		// * WebKit/Blink (Safari, Chrome, Android browser, etc)
		// * Opera
		// * Firefox 18+
		// * Microsoft Edge (Windows 10)
		return window.devicePixelRatio;
	} else if ( window.msMatchMedia !== undefined ) {
		// Windows 8 desktops / tablets, probably Windows Phone 8
		//
		// IE 10/11 doesn't report pixel ratio directly, but we can get the
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
 * Bracket a given device pixel ratio to one of [1, 1.5, 2].
 *
 * This is useful for grabbing images on the fly with sizes based on the display
 * density, without causing slowdown and extra thumbnail renderings on devices
 * that are slightly different from the most common sizes.
 *
 * The bracketed ratios match the default 'srcset' output on MediaWiki thumbnails,
 * so will be consistent with default renderings.
 *
 * @static
 * @inheritable
 * @return {number} Device pixel ratio
 */
$.bracketDevicePixelRatio = function ( baseRatio ) {
	if ( baseRatio > 1.5 ) {
		return 2;
	} else if ( baseRatio > 1 ) {
		return 1.5;
	} else {
		return 1;
	}
};

/**
 * Get reported or approximate device pixel ratio, bracketed to [1, 1.5, 2].
 *
 * This is useful for grabbing images on the fly with sizes based on the display
 * density, without causing slowdown and extra thumbnail renderings on devices
 * that are slightly different from the most common sizes.
 *
 * The bracketed ratios match the default 'srcset' output on MediaWiki thumbnails,
 * so will be consistent with default renderings.
 *
 * - 1.0 means 1 CSS pixel is 1 hardware pixel
 * - 1.5 means 1 CSS pixel is 1.5 hardware pixels
 * - 2.0 means 1 CSS pixel is 2 hardware pixels
 *
 * @static
 * @inheritable
 * @return {number} Device pixel ratio
 */
$.bracketedDevicePixelRatio = function () {
	return $.bracketDevicePixelRatio( $.devicePixelRatio() );
};

/**
 * Implement responsive images based on srcset attributes, if browser has no
 * native srcset support.
 *
 * @return {jQuery} This selection
 * @chainable
 */
$.fn.hidpi = function () {
	var $target = this,
		// TODO add support for dpi media query checks on Firefox, IE
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
				if ( match !== null ) {
					$img.attr( 'src', match );
				}
			}
		} );
	}

	return $target;
};

/**
 * Match a srcset entry for the given device pixel ratio
 *
 * Exposed for testing.
 *
 * @private
 * @static
 * @param {number} devicePixelRatio
 * @param {string} srcset
 * @return {Mixed} null or the matching src string
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
		candidate = candidates[ i ];
		bits = candidate.split( / +/ );
		src = bits[ 0 ];
		if ( bits.length > 1 && bits[ 1 ].charAt( bits[ 1 ].length - 1 ) === 'x' ) {
			ratioStr = bits[ 1 ].slice( 0, -1 );
			ratio = parseFloat( ratioStr );
			if ( ratio <= devicePixelRatio && ratio > selectedRatio ) {
				selectedRatio = ratio;
				selectedSrc = src;
			}
		}
	}
	return selectedSrc;
};

/**
 * @class jQuery
 * @mixins jQuery.plugin.hidpi
 */

}( jQuery ) );
