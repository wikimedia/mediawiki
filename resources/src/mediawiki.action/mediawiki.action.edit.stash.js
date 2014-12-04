/*!
 * Scripts for pre-emptive edit preparing on action=edit
 */
( function ( mw, $ ) {
	$( function () {
		var api = new mw.Api(), pending = null, $form = $( '#editform' );

		function stashEdit( token ) {
			var data = $form.serializeObject();

			pending = api.post( {
				action: 'stashedit',
				token: token,
				title: mw.config.get( 'wgPageName' ),
				section: data.wpSection,
				sectiontitle: '',
				text: data.wpTextbox1,
				contentmodel: data.model,
				contentformat: data.format,
				baserevid: data.parentRevId
			} );
		}

		function onEditChanged() {
			// If a stash request is already in flight, abort it, since its
			// payload has just been invalidated by this change.
			if ( pending ) {
				pending.abort();
			}
			api.getToken( 'edit' ).then( stashEdit );
		}

		// We don't attempt to stash new section edits because in such cases
		// the parser output varies on the edit summary (since it determines
		// the new section's name).
		if ( $form.find( 'input[name=wpSection]' ).val() === 'new' ) {
			return;
		}

		$form.find( '#wpTextbox1' ).on( 'change', onEditChanged );
	} );
}( mediaWiki, jQuery ) );
