( function ( $ ) {
	/**
	 * Get closest scrollable container.
	 *
	 * Traverses up until either a scrollable element or the root is reached, in which case the window
	 * will be returned.
	 *
	 * @param {jQuery} el Element to find scrollable container for
	 * @param {string} [dimension] Dimension of scrolling to look for; `x`, `y` or omit for either
	 * @return {jQuery} Closest scrollable container
	 */
	$.fn.getScrollingParent = function ( dimension ) {
		var i, val,
			props,
			$parent = this.parent();

		if ( this.length === 0 ) {
			return this;
		}

		// Only pay attention to X and Y scrolling
		if ( dimension === 'x' || dimension === 'y' ) {
			props = [ 'overflow', 'overflow-' + dimension ];
		} else {
			props = [ 'overflow', 'overflow-x', 'overflow-y' ];
		}

		while ( $parent.length ) {
			if ( $parent[0] === this[0].ownerDocument.body ) {
				return this.pushStack( $parent.first() );
			}
			i = props.length;
			while ( i-- ) {
				val = $parent.css( props[i] );
				if ( val === 'auto' || val === 'scroll' ) {
					return this.pushStack( $parent.first() );
				}
			}
			$parent = $parent.parent();
		}
		return this.pushStack( $( this[0].ownerDocument.body ) );
	};

	function getNewScrollPosition( elementMin, elementMax, viewportMin, viewportMax ) {
		// Internal function called for X and Y directions, to determine whether / where
		// to scroll to
		// How many pixels too big is this element for the viewport?
		var oversize = ( elementMax - elementMin ) - ( viewportMax - viewportMin ),
			overflow;

		if ( oversize < 0 ) {
			// Sort of a hack:
			// if the element isn't oversized, then give us a bit of leeway
			// at the bottom to allow for debug toolbar, etc
			oversize = -50;
		}

		if ( elementMin >= viewportMin && elementMax <= viewportMax ) {
			// Simple case: element is already within viewport in this direction
			// Do nothing
			return viewportMin;
		} else if ( elementMax > viewportMax ) {
			// Element's maximum edge exceeds the viewport
			// Move the viewport forward by the amount of the overflow,
			// but subtract out amount that the element exceeds the viewport
			// size back in.
			overflow = elementMax - viewportMax;

			return viewportMin + overflow - oversize;
		} else if ( elementMin < viewportMin ) {
			// Element's minimum edge is before the start of the viewport
			// Just set the viewport to the start of the element
			return elementMin;
		}
	}

	/**
	 * Gets border dimensions of this element
	 * @return {Object} Object with the properties 'top', 'left', 'bottom',
	 *   'right', which contain the border width on that side.
	 */
	$.fn.getBorders = function () {
		var el = this[0],
			doc = el.ownerDocument,
			win = doc.parentWindow || doc.defaultView,
			style = win && win.getComputedStyle ?
				win.getComputedStyle( el, null ) :
				el.currentStyle,
			$el = $( el ),
			top = parseFloat( style ? style.borderTopWidth : $el.css( 'borderTopWidth' ) ) || 0,
			left = parseFloat( style ? style.borderLeftWidth : $el.css( 'borderLeftWidth' ) ) || 0,
			bottom = parseFloat( style ? style.borderBottomWidth : $el.css( 'borderBottomWidth' ) ) || 0,
			right = parseFloat( style ? style.borderRightWidth : $el.css( 'borderRightWidth' ) ) || 0;

		return {
			'top': Math.round( top ),
			'left': Math.round( left ),
			'bottom': Math.round( bottom ),
			'right': Math.round( right )
		};
	};

	/**
	 * Scrolls the $parent element such that this element is in view
	 * Based on ideas from jQuery Cookbook, Lindley (2009) pp. 144
	 * and from OOJS' scroll handling.
	 * @param {Object} [config={}] Configuration config
	 * @param {string} [config.duration=fast] jQuery animation duration value
	 * @param {string} [config.direction] Scroll in only one direction, e.g. 'x' or 'y', omit
	 *  to scroll in both directions
	 * @param {Function} [config.complete] Function to call when scrolling completes
	 * @return {jQuery} The current jQuery object, for chaining.
	 */
	$.fn.scrollIntoView = function ( config ) {
		config = config || {};

		var $parent = this.getScrollingParent( config.directions ),
			animateProperties = {},
			parentPos = $parent.offset(),
			elementPos = this.offset(),
			borders = $parent.getBorders(),
			containerCorners = {
				'top' : parentPos.top + borders.top,
				'left' : parentPos.left + borders.left,
				'right' : parentPos.left + $parent.width() - borders.right,
				'bottom' : parentPos.top + $parent.height() - borders.bottom
			},
			elementCorners = {
				'top' : elementPos.top,
				'left' : elementPos.left,
				'right' : elementPos.left + this.width(),
				'bottom' : elementPos.top + this.height()
			};

		if ( config.direction ) {
			if ( config.direction === 'x' ) {
				animateProperties.scrollLeft = getNewScrollPosition(
					elementCorners.left, elementCorners.right,
					containerCorners.left, containerCorners.right
				);
			} else if ( config.direction === 'y' ) {
				animateProperties.scrollTop = getNewScrollPosition(
					elementCorners.top, elementCorners.bottom,
					containerCorners.top, containerCorners.bottom
				);
			}
		} else {
			animateProperties.scrollLeft = getNewScrollPosition(
				elementCorners.left, elementCorners.right,
				containerCorners.left, containerCorners.right
			);

			animateProperties.scrollTop = getNewScrollPosition(
				elementCorners.top, elementCorners.bottom,
				containerCorners.top, containerCorners.bottom
			);
		}

		if ( !$.isEmptyObject( animateProperties ) ) {
			$parent
				.stop( true ) // Stop all animations in case we are already animating
				.animate( animateProperties, config.duration || 'fast' );
			if ( config.complete ) {
				$parent.queue( function ( next ) {
					config.complete();
					next();
				} );
			}
		} else {
			if ( config.complete ) {
				config.complete();
			}
		}
	};
} ( jQuery ) );
