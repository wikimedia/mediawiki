/**
 * jQuery Color Animations
 * Copyright 2007 John Resig
 * Released under the MIT and GPL licenses.
 *
 * - 2011-01-05: Modified by Krinkle to use the jQuery.colorUtil plugin (which has to be loaded first!)
 */
(function( $ ) {

	// We override the animation for all of these color styles
	$.each(['backgroundColor', 'borderBottomColor', 'borderLeftColor', 'borderRightColor', 'borderTopColor', 'color', 'outlineColor'],
		function( i, attr ) {
			$.fx.step[attr] = function( fx ) {
				if ( fx.state == 0 ) {
					fx.start = getColor( fx.elem, attr );
					fx.end = $.colorUtil.getRGB( fx.end );
				}
		
				fx.elem.style[attr] = 'rgb(' + [
					Math.max(Math.min( parseInt((fx.pos * (fx.end[0] - fx.start[0])) + fx.start[0]), 255), 0),
					Math.max(Math.min( parseInt((fx.pos * (fx.end[1] - fx.start[1])) + fx.start[1]), 255), 0),
					Math.max(Math.min( parseInt((fx.pos * (fx.end[2] - fx.start[2])) + fx.start[2]), 255), 0)
				].join( ',' ) + ')';
			}
		}
	);
	
	function getColor(elem, attr) {
		var color;

		do {
			color = $.curCSS(elem, attr);

			// Keep going until we find an element that has color, or we hit the body
			if ( color != '' && color != 'transparent' || $.nodeName(elem, 'body') )
				break; 

			attr = 'backgroundColor';
		} while ( elem = elem.parentNode );

		return $.colorUtil.getRGB(color);
	};
	
} )( jQuery );
