/**
 * @class jQuery.plugin.footHovzer
 */
( function ( $ ) {
	var $hovzer, footHovzer, prevHeight, newHeight;

	function getHovzer() {
		if ( $hovzer === undefined ) {
			$hovzer = $( '<div id="jquery-foot-hovzer"></div>' ).appendTo( 'body' );
		}
		return $hovzer;
	}

	/**
	 * Utility to stack stuff in an overlay fixed on the bottom of the page.
	 *
	 * Usage:
	 *
	 *     var hovzer = $.getFootHovzer();
	 *     hovzer.$.append( $myCollection );
	 *     hovzer.update();
	 *
	 * @static
	 * @inheritable
	 * @return {jQuery.footHovzer}
	 */
	$.getFootHovzer = function () {
		footHovzer.$ = getHovzer();
		return footHovzer;
	};

	/**
	 * @private
	 * @class jQuery.footHovzer
	 */
	footHovzer = {

		/**
		 * @property {jQuery} $ The stack container
		 */

		/**
		 * Update dimensions of stack to account for changes in the subtree.
		 */
		update: function () {
			var $body;

			$body = $( 'body' );
			if ( prevHeight === undefined ) {
				prevHeight = getHovzer().outerHeight( /* includeMargin = */ true );
				$body.css( 'paddingBottom', '+=' + prevHeight + 'px' );
			} else {
				newHeight = getHovzer().outerHeight( true );
				$body.css( 'paddingBottom', ( parseFloat( $body.css( 'paddingBottom' ) ) - prevHeight ) + newHeight );

				prevHeight = newHeight;
			}
		}
	};

	/**
	 * @class jQuery
	 * @mixins jQuery.plugin.footHovzer
	 */

}( jQuery ) );
