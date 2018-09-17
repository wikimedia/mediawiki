/**
 * @private
 * @class jQuery.plugin.footHovzer
 */
( function () {
	var $hovzer, footHovzer, $spacer;

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

			if ( $spacer === undefined ) {
				$spacer = $( '<div>' ).attr( 'id', 'jquery-foot-hovzer-spacer' );
				$spacer.appendTo( $body );
			}
			// Ensure CSS is applied by browser before using .outerHeight()
			setTimeout( function () {
				$spacer.css( 'height', getHovzer().outerHeight( /* includeMargin = */ true ) );
			}, 0 );
		}
	};

	/**
	 * @class jQuery
	 * @mixins jQuery.plugin.footHovzer
	 */

}() );
