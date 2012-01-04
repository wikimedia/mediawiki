/**
 * Utility to stack stuff in an overlay fixed on the bottom of the page.
 *
 * Usage:
 * <code>
 *     var hovzer = $.getFootHovzer();
 *     hovzer.$.append( $myCollection );
 *     hovzer.update();
 * </code>
 *
 * @author Timo Tijhof, 2012
 */
( function ( $ ) {
	var $hovzer, footHovzer, prevHeight, newHeight;

	function getHovzer() {
		if ( $hovzer === undefined ) {
			$hovzer = $( '<div id="jquery-foot-hovzer"></div>' ).appendTo( 'body' );
		}
		return $hovzer;
	}

	footHovzer = {
		update: function () {
			var $body, winTop;

			$body = $( 'body' );
			if ( prevHeight === undefined ) {
				prevHeight = getHovzer().outerHeight( /*includeMargin=*/true );
				$body.css( 'paddingBottom', '+=' + prevHeight + 'px' );
			} else {
				newHeight = getHovzer().outerHeight( true );
				$body.css( 'paddingBottom', ( parseFloat( $body.css( 'paddingBottom' ) ) - prevHeight ) + newHeight );
				// Update scroll so that page stays focusses on same area
				winTop = $(window).scrollTop();
				if ( $(document).height() - $(window).height() > winTop ) {
					$(window).scrollTop( winTop + ( newHeight - prevHeight ) );
				}
				
				prevHeight = newHeight;
			}
		}
	};

	$.getFootHovzer = function () {
		footHovzer.$ = getHovzer();
		return footHovzer;
	};

}( jQuery ) );
