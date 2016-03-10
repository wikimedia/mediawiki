( function ( mw, $ ) {
	'use strict';

	/**
	 * Utility library for viewport-related functions
	 *
	 * Notable references:
	 * - https://github.com/tuupola/jquery_lazyload
	 * - https://github.com/luis-almeida/unveil
	 *
	 * @class mw.viewport
	 * @singleton
	 */
	var viewport = {

		/**
		 * This is a private method pulled inside the module for testing purposes.
		 *
		 * @ignore
		 * @private
		 */
		makeViewportFromWindow: function () {
			var $window = $( window ),
				scrollTop = $window.scrollTop(),
				scrollLeft = $window.scrollLeft();

			return {
				top: scrollTop,
				left: scrollLeft,
				right: scrollLeft + $window.width(),
				bottom: ( window.innerHeight ? window.innerHeight : $window.height() ) + scrollTop
			};
		},

		/**
		 * Check if any part of a given element is in a given viewport
		 *
		 * @method
		 * @param {HTMLElement} el Element that's being tested
		 * @param {Object} [rectangle] Viewport to test against; structured as such:
		 *
		 *	var rectangle = {
		 *		top: topEdge,
		 *		left: leftEdge,
		 *		right: rightEdge,
		 *		bottom: bottomEdge
		 *	}
		 *	Defaults to viewport made from `window`.
		 *
		 * @return {boolean}
		 */
		isElementInViewport: function ( el, rectangle ) {
			var $el = $( el ),
				offset = $el.offset(),
				rect = {
					height: $el.height(),
					width: $el.width(),
					top: offset.top,
					left: offset.left
				},
				viewport = rectangle || this.makeViewportFromWindow();

			return (
				// Top border must be above viewport's bottom
				( viewport.bottom >= rect.top ) &&
				// Left border must be before viewport's right border
				( viewport.right >= rect.left ) &&
				// Bottom border must be below viewport's top
				( viewport.top <= rect.top + rect.height ) &&
				// Right border must be after viewport's left border
				( viewport.left <= rect.left + rect.width )
			);
		},

		/**
		 * Check if an element is a given threshold away in any direction from a given viewport
		 *
		 * @method
		 * @param {HTMLElement} el Element that's being tested
		 * @param {number} [threshold] Pixel distance considered "close". Must be a positive number.
		 *  Defaults to 50.
		 * @param {Object} [rectangle] Viewport to test against.
		 *  Defaults to viewport made from `window`.
		 * @return {boolean}
		 */
		isElementCloseToViewport: function ( el, threshold, rectangle ) {
			var viewport = rectangle ? $.extend( {}, rectangle ) : this.makeViewportFromWindow();
			threshold = threshold || 50 ;

			viewport.top -= threshold;
			viewport.left -= threshold;
			viewport.right += threshold;
			viewport.bottom += threshold;
			return this.isElementInViewport( el, viewport );
		}

	};

	mw.viewport = viewport;
}( mediaWiki, jQuery ) );
