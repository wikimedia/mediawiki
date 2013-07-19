( function ( mw, $ ) {
	function handleAgoraCheckChangeEvent() {
		var $check = $( this ),
			enabled = $check.prop( 'checked' ),
			$label = $check.closest( '.mw-ui-check-label' );

		$label.toggleClass( 'mw-ui-checked', enabled );
	}

	$( function () {
		// Add the styling (disabled by default so the form is
		// workable without JavaScript)
		$( '.mw-ui-check-label' ).addClass( 'agora' );
		$( '.mw-ui-checkbox' ).change( handleAgoraCheckChangeEvent );
	} );
}( mediaWiki, jQuery ) );
