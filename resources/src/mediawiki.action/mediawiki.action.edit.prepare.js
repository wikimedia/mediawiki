/*!
 * Scripts for pre-emptive edit preparing on action=edit
 */
( function ( mw, $ ) {

	// Pre-render pages while users type edit summaries
	$( function () {
		var prepareBusy = false, textChanged = false;

		$( '#wpTextbox1, #wpSummary' ).change( function () {
			textChanged = true;
		} );

		$( '#wpSummary' ).focus( function () {
			var api, sectionId;

			// Avoid doing duplicated work
			if ( prepareBusy || !textChanged ) {
				return;
			}
			prepareBusy = true;
			textChanged = false;

			sectionId = $( '#editform input[name="wpSection"]' ).val();

			api = new mw.Api();
			api.postWithToken( 'csrf', {
				action: 'prepareedit',
				title: mw.config.get( 'wgPageName' ),
				section: sectionId,
				sectiontitle: sectionId === 'new' ? $( '#wpSummary' ).val() : '',
				text: $( '#wpTextbox1' ).val(),
				contentmodel: $( '#editform input[name="model"]' ).val(),
				contentformat: $( '#editform input[name="format"]' ).val(),
				baserevid: $( '#editform input[name="parentRevId"]' ).val()
			} ).always( function () {
				prepareBusy = false;
			} );
		} );
	} );

}( mediaWiki, jQuery ) );
