( function ( mw, $ ) {
	'use strict';

	/**
	 * Private helper method for converting `window` to usable viewport object
	 *
	 * @method
	 * @return {Object}
	 */
	function makeViewportFromWindow() {
		var $window = $( window );

		return {
			top: $window.scrollTop(),
			left: $window.scrollLeft(),
			right: $window.scrollLeft() + $window.width(),
			bottom: ( window.innerHeight ? window.innerHeight : $window.height() ) + $window.scrollTop()
		};
	}

	/**
	 * Utility library for viewport-related functions
	 * @class mw.viewport
	 * @singleton
	 */
	var viewport = {

		/**
		 * Check if any part of a given element is in a given viewport
		 *
		 * @method
		 * @param {jQuery} $el Element that's being tested
		 * @param {Object} rectangle Viewport to test against; structured as such:
		 *
		 *	var rectangle = {
		 *		top: topEdge,
		 *		left: leftEdge,
		 *		right: rightEdge,
		 *		bottom: bottomEdge
		 *	}
		 *	Defaults to current `window`.
		 *
		 * @return {boolean}
		 */
		isElementInViewport: function ( $el, rectangle ) {
			var elOffset = $el.offset();

			rectangle = rectangle || makeViewportFromWindow();

			return (
				( rectangle.bottom >= elOffset.top ) &&
				( rectangle.right >= elOffset.left ) &&
				( rectangle.top <= elOffset.top + $el.height() ) &&
				( rectangle.left <= elOffset.left + $el.width() )
			);
		},

		/**
		 * Alias for testing element against current viewport
		 *
		 * @method
		 * @param {jQuery} $el Element that's being tested
		 * @return {boolean}
		 */
		isElementOnScreen: function ( $el ) {
			return this.isElementInViewport( $el );
		},

		/**
		 * Check if an element is a given threshold away from a given viewport
		 *
		 * @method
		 * @param {jQuery} $el Element that's being tested
		 * @param {number} threshold Pixel distance considered "close". Defaults to 50.
		 * @param {Object} rectangle Viewport to test against. Defaults to current `window`.
		 * @return {boolean}
		 */
		isElementCloseToViewport: function ( $el, threshold, rectangle ) {
			rectangle = rectangle || makeViewportFromWindow();
			threshold = ( threshold && threshold >= 0 ? threshold : 50 );

			rectangle.top -= threshold;
			rectangle.left -= threshold;
			rectangle.right += threshold;
			rectangle.bottom += threshold;

			return this.isElementInViewport( $el, rectangle );
		}

	};

	mw.viewport = viewport;
}( mediaWiki, jQuery ) );
