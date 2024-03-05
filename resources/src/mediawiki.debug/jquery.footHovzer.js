/**
 * @private
 */
( function () {
	var $hovzer, footHovzer, $spacer;

	function getHovzer() {
		if ( $hovzer === undefined ) {
			$hovzer = $( '<div>' ).attr( 'id', 'jquery-foot-hovzer' ).appendTo( document.body );
		}
		return $hovzer;
	}

	/**
	 * Utility to stack stuff in an overlay fixed on the bottom of the page.
	 *
	 * @example
	 * var hovzer = $.getFootHovzer();
	 * hovzer.$.append( $myCollection );
	 * hovzer.update();
	 *
	 * @private
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

			$body = $( document.body );

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
	 * @mixes jQuery.plugin.footHovzer
	 */

}() );
