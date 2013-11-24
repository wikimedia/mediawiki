/**
 * jQuery Color Animations
 *
 * @author John Resig, 2007
 * @author Krinkle, 2011
 * Released under the MIT and GPL licenses.
 *
 * - 2011-01-05: Forked for MediaWiki. See also jQuery.colorUtil plugin
 */
( function ( $ ) {

	function getColor( elem, attr ) {
		/*jshint boss:true */
		var color;

		do {
			color = $.curCSS( elem, attr );

			// Keep going until we find an element that has color, or we hit the body
			if ( color !== '' && color !== 'transparent' || $.nodeName( elem, 'body' ) ) {
				break;
			}

			attr = 'backgroundColor';
		} while ( elem = elem.parentNode );

		return $.colorUtil.getRGB( color );
	}

	// We override the animation for all of these color styles
	$.each([
		'backgroundColor',
		'borderBottomColor',
		'borderLeftColor',
		'borderRightColor',
		'borderTopColor',
		'color',
		'outlineColor'
	], function ( i, attr ) {
		$.fx.step[attr] = function ( fx ) {
			if ( fx.state === 0 ) {
				fx.start = getColor( fx.elem, attr );
				fx.end = $.colorUtil.getRGB( fx.end );
			}

			fx.elem.style[attr] = 'rgb(' + [
				Math.max( Math.min( parseInt( (fx.pos * (fx.end[0] - fx.start[0])) + fx.start[0], 10 ), 255 ), 0 ),
				Math.max( Math.min( parseInt( (fx.pos * (fx.end[1] - fx.start[1])) + fx.start[1], 10 ), 255 ), 0 ),
				Math.max( Math.min( parseInt( (fx.pos * (fx.end[2] - fx.start[2])) + fx.start[2], 10 ), 255 ), 0 )
			].join( ',' ) + ')';
		};
	} );

}( jQuery ) );
