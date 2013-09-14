/**
 * JavaScript to show jump links to motor-impaired users when they are focused.
 */
jQuery( function ( $ ) {

	$( '.mw-jump' ).on( 'focus blur click', 'a', function ( e ) {
		// Confusingly jQuery leaves e.type as focusout for delegated blur events
		if ( e.type === 'click' ) {
			// Make sure the element we link is tabbable by the time the
			// user clicks on the link (needed for WebKit/ Blink).
			$( $( this ).attr( 'href' ) )
				.attr( 'tabindex', 1 )
				.blur( function () {
					// Remove the tabindex again
					$( this ).removeAttr( 'tabindex' );
				} );
		} else if ( e.type === 'blur' || e.type === 'focusout' ) {
			$( this ).closest( '.mw-jump' ).css({ height: 0 });
		} else {
			$( this ).closest( '.mw-jump' ).css({ height: 'auto' });
		}
	} );

} );
