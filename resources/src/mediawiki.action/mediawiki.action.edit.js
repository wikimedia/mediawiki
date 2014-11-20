/*!
 * Scripts for action=edit
 */
( function ( mw, $ ) {

	$( function () {
		var editBox, scrollTop, $editForm;

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
		isBusy = false; // poor mans debounce
		// @TODO: don't trigger unless textarea changed or section=new and the summary changed
		$labelBox = $( '#wpSummary' );
		$labelBox.focus( function() {
			if ( isBusy ) {
				return;
			}
			isBusy = true;
			api = new mw.Api();
			api.post( {
				action: 'prepareedit',
				title: mw.config.get( 'wgPageName' ),
				section: $( '#wpSection' ).val(),
				sectionTitle : $( '#wpSection' ).val() === 'all' ? $labelBox.val() : '',
				text : editBox.value
			} ).done ( function ( data ) {
				isBusy = false;
				console.log( data ); // debug
			} );
		} );
	} );

}( mediaWiki, jQuery ) );
