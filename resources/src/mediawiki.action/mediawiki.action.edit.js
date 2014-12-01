/*!
 * Scripts for action=edit
 */
( function ( mw, $ ) {

	$( function () {
		var $editForm = $( '#editform' ),
			$editBox = $editForm.find( '#wpTextbox1' ),
			$scrollTop = $editForm.find( '#wpScrolltop' );

		// Make sure edit summary does not exceed byte limit
		$( '#wpSummary' ).byteLimit( 255 );

		// Restore the edit box scroll state following a preview operation,
		// and set up a form submission handler to remember this state.
		if ( $scrollTop.val() ) {
			$editBox.scrollTop( $scrollTop.val() );
		}

		$editForm.submit( function () {
			$scrollTop.val( $editBox.scrollTop() );
		} );

		$editBox.change( mw.hook( 'wikipage.content.change' ).fire );
	} );

}( mediaWiki, jQuery ) );
