/*!
 * Scripts for action=edit at domready
 */
( function( $, mw ) {
	$( function ( $ ) {
		var editBox, scrollTop, $editForm;

		// Make sure edit summary does not exceed byte limit
		$( '#wpSummary' ).byteLimit( {
			byte: 767,
			codepoint: mw.config.get( 'wgMaxEditSummaryLength' )
		} );

		// Restore the edit box scroll state following a preview operation,
		// and set up a form submission handler to remember this state.
		editBox = document.getElementById( 'wpTextbox1' );
		scrollTop = document.getElementById( 'wpScrolltop' );
		$editForm = $( '#editform' );
		if ( $editForm.length && editBox && scrollTop ) {
			if ( scrollTop.value ) {
				editBox.scrollTop = scrollTop.value;
			}
			$editForm.submit( function () {
				scrollTop.value = editBox.scrollTop;
			} );
		}
	} )
} )( jQuery, mediaWiki );
