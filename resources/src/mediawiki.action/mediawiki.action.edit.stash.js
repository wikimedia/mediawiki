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
				sectiontitle: data.wpSection === 'new' ? data.wpSummary : '',
				text: data.wpTextbox1,
				contentmodel: data.model,
				contentformat: data.format,
				baserevid: data.parentRevId
			} );
		}

		$form.on( 'change', function () {
			if ( pending ) {
				pending.abort();
			}
			api.getToken( 'edit' ).then( stashEdit );
		} );
	} );
}( mediaWiki, jQuery ) );
