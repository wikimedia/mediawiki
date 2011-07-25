/**
 * Utility functions for jazzing up HTMLForm elements
 */
( function( $ ) { 

/**
 * jQuery plugin to fade or snap to visible state.
 *
 * @param boolean instantToggle (optional)
 * @return jQuery
 */
$.fn.goIn = function( instantToggle ) {
	if ( instantToggle === true ) {
		return $(this).show();
	}
	return $(this).stop( true, true ).fadeIn();
};

/**
 * jQuery plugin to fade or snap to hiding state.
 *
 * @param boolean instantToggle (optional)
 * @return jQuery
 */
$.fn.goOut = function( instantToggle ) {
	if ( instantToggle === true ) {
		return $(this).hide();
	}
	return $(this).stop( true, true ).fadeOut();
};

/**
 * Bind a function to the jQuery object via live(), and also immediately trigger
 * the function on the objects with an 'instant' paramter set to true
 * @param callback function taking one paramter, which is Bool true when the event
 *     is called immediately, and the EventArgs object when triggered from an event
 */
$.fn.liveAndTestAtStart = function( callback ){
	$(this)
		.live( 'change', callback )
		.each( function( index, element ){
			callback.call( this, true );
		} );
};

// Document ready:
$( function() {

	// Animate the SelectOrOther fields, to only show the text field when
	// 'other' is selected.
	$( '.mw-htmlform-select-or-other' ).liveAndTestAtStart( function( instant ) {
		var $other = $( '#' + $(this).attr( 'id' ) + '-other' );
		$other = $other.add( $other.siblings( 'br' ) );
		if ( $(this).val() === 'other' ) {
			$other.goIn( instant );
		} else {
			$other.goOut( instant );
		}
	});

});


})( jQuery );
