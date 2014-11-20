/*!
 * Scripts for pre-emptive edit preparing on action=edit
 */
( function ( mw, $ ) {
	$( function () {
		var api = new mw.Api(),
			request = api.getToken( 'edit' ),
			$form = $( '#editform' ),
			queued = 0;

		function stashEdit( token ) {
			queued--;

			var data = $form.serializeObject();

			return api.post( {
				action: 'stashedit',
				token: token,
				title: mw.config.get( 'wgPageName' ),
				section: data.wpSection,
				sectiontitle: data.wpSection === 'new' ? data.wpSummary : '',
				text: data.wpTextbox1,
				contentmodel: data.model,
				contentformat: data.format,
				baserevid: data.parentRevId
			} ).then( function () {
				return api.getToken( 'edit' );
			} );
		}

		$form.on( 'change', function () {
			// If a call to stashEdit() is already pending, we don't need
			// to enqueue another one, even if the user has made additional
			// changes. This is because the queued stashEdit() call will
			// submit the content of the edit form as it is at the time it
			// is invoked. Since the invocation will happen in the future,
			// it will include this change.
			if ( queued === 0 ) {
				queued++;
				request = request.then( stashEdit );
			}
		} );
	} );
}( mediaWiki, jQuery ) );
