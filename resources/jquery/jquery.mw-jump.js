/**
 * JavaScript to show jump links to motor-impaired users when they are focused.
 */
jQuery( function ( $ ) {

	$( '.mw-jump' ).on( 'focus blur keypress', 'a', function ( e ) {
		// Confusingly jQuery leaves e.type as focusout for delegated blur events
		if ( e.type === 'keypress' ) {
			if ( e.keyCode === 14 ) {
				var $dest = $( $( this ).attr( 'href' ) );
				$dest.find( ':tabbable:first' ).focus();
				e.preventDefault();
			}
		} else if ( e.type === 'blur' || e.type === 'focusout' ) {
			$( this ).closest( '.mw-jump' ).css({ height: 0 });
		} else {
			$( this ).closest( '.mw-jump' ).css({ height: 'auto' });
		}
	} );

} );
