/*!
 * Scripts for pre-emptive edit preparing on action=edit
 */
( function ( mw, $ ) {
	var api = new mw.Api(), request = api.getToken( 'edit' );

	function stashEdit( token ) {
		var data = $( '#editform' ).serializeObject();

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

	mw.hook( 'editing.content.changed' ).add( function () {
		request = request.then( stashEdit );
	} );

}( mediaWiki, jQuery ) );
