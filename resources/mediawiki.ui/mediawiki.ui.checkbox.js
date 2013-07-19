( function ( mw, $ ) {
	function handleAgoraCheckChangeEvent() {
		var $check = $( this ),
			enabled = $check.is( ':checked' ),
			$label = $check.closest( '.mw-ui-check-label' );

		if ( enabled ) {
			$label.addClass( 'mw-ui-checked' );
		} else {
			$label.removeClass( 'mw-ui-checked' );
		}
	}

	$( function () {
		// Add the styling (disabled by default so the form is
		// workable without JavaScript)
		$( '.mw-ui-check-label' ).addClass( 'agora' );
		$( '.mw-ui-checkbox' ).change( handleAgoraCheckChangeEvent );
	} );
}( mediaWiki, jQuery ) );
