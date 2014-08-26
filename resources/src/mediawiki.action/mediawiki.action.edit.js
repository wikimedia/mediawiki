( function ( mw, $ ) {

	$( function () {
		var editBox = document.getElementById( 'wpTextbox1' ),
			scrollTop = document.getElementById( 'wpScrolltop' ),
			$editForm = $( '#editform' );

		// Make sure edit summary does not exceed byte limit
		$( '#wpSummary' ).byteLimit( 255 );

		// Restore the edit box scroll state following a preview operation,
		// and set up a form submission handler to remember this state.
		if ( $editForm.length && editBox && scrollTop ) {
			if ( scrollTop.value ) {
				editBox.scrollTop = scrollTop.value;
			}
			$editForm.submit( function () {
				scrollTop.value = editBox.scrollTop;
			} );
		}
	} );

}( mediaWiki, jQuery ) );
