( function ( mw, $ ) {
	'use strict';

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
		 *		bot: bottomEdge
		 *	}
		 *
		 * @return {boolean}
		 */
		isElementInViewport: function ( $el, rectangle ) {
			var viewport = rectangle,
				$window = $( window ),
				elOffset = $el.offset();

			if ( rectangle === undefined || rectangle === window ) {
				viewport = {
					top: $window.scrollTop(),
					left: $window.scrollLeft(),
					right: $window.scrollLeft() + $window.width(),
					bot: ( window.innerHeight ? window.innerHeight : $window.height ) + $window.scrollTop()
				};
			}

			return (
				( viewport.bot >= elOffset.top ) &&
				( viewport.right >= elOffset.left ) &&
				( viewport.top <= elOffset.top + $el.height() ) &&
				( viewport.left <= elOffset.left + $el.width() )
			);
		},

		/**
		 * Alias for testing element against current viewport
		 *
		 * @method
		 * @param {jQuery} $el Element that's being tested
		 * @return {boolean}
		 */
		isElementVisible: function ( $el ) {
			return this.isElementInViewport( $el, window );
		}
	};

	mw.viewport = viewport;
}( mediaWiki, jQuery ) );
