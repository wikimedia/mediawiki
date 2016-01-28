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
		 * @param {object} rectangle Viewport to test against; structured as such:
		 *
		 *	var rectangle = {
		 *		'top': topEdge,
		 *		'left': leftEdge,
		 *		'right': rightEdge,
		 *		'bot': bottomEdge
		 *	}
		 *
		 * @return {boolean}
		 */
		isElementInViewport: function ( $el, rectangle ) {
			var vport = rectangle,
				$window = $( window ),
				elOffset = $el.offset();

			if ( rectangle === undefined || rectangle === window ) {
				vport = {
					'top': $window.scrollTop(),
					'left': $window.scrollLeft(),
					'right': $window.scrollLeft() + $window.width(),
					'bot': ( window.innerHeight ? window.innerHeight : $window.height ) + $window.scrollTop()
				};
			}

			return (
				( vport.bot >= elOffset.top ) &&
				( vport.right >= elOffset.left ) &&
				( vport.top <= elOffset.top + $el.height() ) &&
				( vport.left <= elOffset.left + $el.width() )
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