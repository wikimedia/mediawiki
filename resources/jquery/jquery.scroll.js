( function( $ ) {
	/**
	 * Scrolls the $parent element such that this element is in view
	 * Based on ideas from jQuery Cookbook, Lindley (2009) pp. 144
	 * @param  {jQuery}  $parent          The object to scroll. Optional, defaults to whole window.
	 * @param  {Object} animateDirections Which directions to consider.
	 * Object of the form {X : true|false, Y : true|false}.
	 * Optional, default is Y direction only.
	 * @param  {Object} animateOptions    Stuff to pass to animate's options parameter. Optional.
	 * @return jQuery                     The current jQuery object, for chaining.
	 */
	$.fn.scrollIntoView = function( $parent, animateDirections, animateOptions ) {
		var $scrollItem,
			$viewportItem,
			$element = $( this ),
			elementRect,
			viewportRect,
			getNewPosition = function( elementMin, elementMax, viewportMin, viewportMax ) {
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
			},
			animateTarget = {},
			XPos,
			YPos;


		if ( ! $parent ) {
			$scrollItem = $( 'body, html' );
			$viewportItem = $( window );
		} else {
			$scrollItem = $viewportItem = $parent;
		}

		if ( ! animateDirections ) {
			animateDirections = {};
		}

		if ( ! animateOptions ) {
			animateOptions = {};
		}

		animateDirections = $.extend(
				{
					'X' : false,
					'Y' : true
				},
				animateDirections
			);

		elementRect = {
			minX : $element.offset().left,
			minY : $element.offset().top,
			maxX : $element.offset().left + $element.width(),
			maxY : $element.offset().top + $element.height()
		};

		viewportRect = {
			minX : $scrollItem.scrollLeft(),
			minY : $scrollItem.scrollTop(),
			maxX : $scrollItem.scrollLeft() + $viewportItem.width(),
			maxY : $scrollItem.scrollTop() + $viewportItem.height()
		};

		if ( animateDirections.X ) {
			XPos = getNewPosition( elementRect.minX, elementRect.maxX, viewportRect.minX, viewportRect.maxX );
			if ( XPos !== viewportRect.minX ) {
				animateTarget.scrollLeft = XPos;
			}
		}

		if ( animateDirections.Y ) {
			YPos = getNewPosition( elementRect.minY, elementRect.maxY, viewportRect.minY, viewportRect.maxY );
			if ( YPos !== viewportRect.minY ) {
				animateTarget.scrollTop = YPos;
			}
		}

		if (
			animateTarget.scrollTop !== undefined ||
			animateTarget.scrollLeft !== undefined
		) {
			$scrollItem.animate( animateTarget, animateOptions );
		} else if ( animateOptions.complete ) {
			$scrollItem.each( function() {
				animateOptions.complete.apply( this );
			} );
		}

		return $element;
	};
} )
( jQuery );
