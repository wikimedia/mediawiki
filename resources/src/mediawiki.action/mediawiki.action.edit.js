/*!
 * Scripts for action=edit
 */
( function ( mw, $ ) {

	$( function () {
		var editBox, scrollTop, $editForm, $labelBox,
			prepareBusy = false, textChanged = false;

		// Make sure edit summary does not exceed byte limit
		$( '#wpSummary' ).byteLimit( 255 );

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

		// Pre-render pages while users type edit summaries
		// @TODO: move elsewhere or put behind a config flag
		$( '#wpTextbox1, #wpSummary' ).change( function () {
			textChanged = true;
		} );
		$labelBox = $( '#wpSummary' );
		$labelBox.focus( function () {
			var api = new mw.Api();
			api.getToken( 'csrf' ).then( function ( token ) {
				var sectionId;
				// Avoid duplicated work
				if ( prepareBusy || !textChanged ) {
					return;
				}
				prepareBusy = true;
				textChanged = false;

				sectionId = $( '#editform input[name="wpSection"]' ).val();

				api.post( {
					action: 'prepareedit',
					title: mw.config.get( 'wgPageName' ),
					section: sectionId,
					sectionTitle: sectionId === 'new' ? $labelBox.val() : '',
					text: editBox.value,
					model: $( '#editform input[name="model"]' ).val(),
					format: $( '#editform input[name="format"]' ).val(),
                    baseRevId: $( '#editform input[name="oldid"]' ).val(),
					token: token
				} ).always ( function () {
					prepareBusy = false;
				} );
			} );
		} );
	} );

}( mediaWiki, jQuery ) );
