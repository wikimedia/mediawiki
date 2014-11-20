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
			var api, sectionId;

			if ( prepareBusy || !textChanged ) {
				return; // avoid duplicated work
			}
			prepareBusy = true;
			textChanged = false;

			sectionId = $( '#editform input[name="wpSection"]' ).val();

			api = new mw.Api();
			api.postWithToken( 'csrf', {
				action: 'prepareedit',
				title: mw.config.get( 'wgPageName' ),
				section: sectionId,
				sectiontitle: sectionId === 'new' ? $labelBox.val() : '',
				text: editBox.value,
				contentmodel: $( '#editform input[name="model"]' ).val(),
				contentformat: $( '#editform input[name="format"]' ).val(),
				baserevid: $( '#editform input[name="sectionRevId"]' ).val()
			} ).always( function () {
				prepareBusy = false;
			} );
		} );
	} );

}( mediaWiki, jQuery ) );
