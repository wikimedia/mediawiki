/*!
 * jQuery arrowSteps plugin
 * Copyright Neil Kandalgaonkar, 2010
 *
 * This work is licensed under the terms of the GNU General Public License,
 * version 2 or later.
 * (see http://www.fsf.org/licensing/licenses/gpl.html).
 * Derivative works and later versions of the code must be free software
 * licensed under the same or a compatible license.
 */

/**
 * @class jQuery.plugin.arrowSteps
 */
( function ( $ ) {
	/**
	 * Show users their progress through a series of steps, via a row of items that fit
	 * together like arrows. One item can be highlighted at a time.
	 *
	 *     <ul id="robin-hood-daffy">
	 *       <li id="guard"><div>Guard!</div></li>
	 *       <li id="turn"><div>Turn!</div></li>
	 *       <li id="parry"><div>Parry!</div></li>
	 *       <li id="dodge"><div>Dodge!</div></li>
	 *       <li id="spin"><div>Spin!</div></li>
	 *       <li id="ha"><div>Ha!</div></li>
	 *       <li id="thrust"><div>Thrust!</div></li>
	 *     </ul>
	 *
	 *     <script>
	 *       $( '#robin-hood-daffy' ).arrowSteps();
	 *     </script>
	 *
	 * @return {jQuery}
	 * @chainable
	 */
	$.fn.arrowSteps = function () {
		var $steps, width, arrowWidth, $stepDiv,
			$el = this,
			paddingSide = $( 'body' ).hasClass( 'rtl' ) ? 'padding-left' : 'padding-right';

		$el.addClass( 'arrowSteps' );
		$steps = $el.find( 'li' );

		width = parseInt( 100 / $steps.length, 10 );
		$steps.css( 'width', width + '%' );

		// Every step except the last one has an arrow pointing forward:
		// at the right hand side in LTR languages, and at the left hand side in RTL.
		// Also add in the padding for the calculated arrow width.
		$stepDiv = $steps.filter( ':not(:last-child)' ).addClass( 'arrow' ).find( 'div' );

		// Execute when complete page is fully loaded, including all frames, objects and images
		$( window ).load( function () {
			arrowWidth = parseInt( $el.outerHeight(), 10 );
			$stepDiv.css( paddingSide, arrowWidth.toString() + 'px' );
		} );

		$el.data( 'arrowSteps', $steps );

		return this;
	};

	/**
	 * Highlights the element selected by the selector.
	 *
	 *       $( '#robin-hood-daffy' ).arrowStepsHighlight( '#guard' );
	 *       // 'Guard!' is highlighted.
	 *
	 *       // ... user completes the 'guard' step ...
	 *
	 *       $( '#robin-hood-daffy' ).arrowStepsHighlight( '#turn' );
	 *       // 'Turn!' is highlighted.
	 *
	 * @param {string} selector
	 */
	$.fn.arrowStepsHighlight = function ( selector ) {
		var $previous,
			$steps = this.data( 'arrowSteps' );
		$.each( $steps, function ( i, step ) {
			var $step = $( step );
			if ( $step.is( selector ) ) {
				if ( $previous ) {
					$previous.addClass( 'tail' );
				}
				$step.addClass( 'head' );
			} else {
				$step.removeClass( 'head tail lasthead' );
			}
			$previous = $step;
		} );
	};

	/**
	 * @class jQuery
	 * @mixins jQuery.plugin.arrowSteps
	 */
}( jQuery ) );
