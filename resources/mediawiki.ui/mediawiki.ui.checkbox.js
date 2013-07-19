( function ( mw, $ ) {
	function handleAgoraCheckChangeEvent() {
		var $check = $( this ),
			enabled = $check.prop( 'checked' ),
			$label = $check.closest( '.mw-ui-check-label' );

		$label.toggleClass( 'mw-ui-checked', enabled );
	}

	$( function () {
		$( '.mw-ui-checkbox' ).change( handleAgoraCheckChangeEvent );
	} );
}( mediaWiki, jQuery ) );
