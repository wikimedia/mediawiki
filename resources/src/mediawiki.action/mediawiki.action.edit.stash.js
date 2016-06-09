/*!
 * Scripts for pre-emptive edit preparing on action=edit
 */
( function ( mw, $ ) {
	if ( !mw.config.get( 'wgAjaxEditStash' ) ) {
		return;
	}

	$( function () {
		var idleTimeout = 3000,
			api = new mw.Api(),
			pending = null,
			$form = $( '#editform' ),
			$text = $form.find( '#wpTextbox1' ),
			$summary = $form.find( '#wpSummary' ),
			data = {},
			timer = null;

		// Send a request to stash the edit to the API.
		// If a request is in progress, abort it since its payload is stale and the API
		// may limit concurrent stash parses.
		function stashEdit() {
			if ( pending ) {
				pending.abort();
			}

			api.getToken( 'csrf' ).then( function ( token ) {
				data = $form.serializeObject();

				pending = api.post( {
					action: 'stashedit',
					token: token,
					title: mw.config.get( 'wgPageName' ),
					section: data.wpSection,
					sectiontitle: '',
					text: data.wpTextbox1,
					summary: data.wpSummary,
					contentmodel: data.model,
					contentformat: data.format,
					baserevid: data.parentRevId
				} );
			} );
		}

		// Check if edit body text changed since the last stashEdit() call or if no edit
		// stash calls have yet been made
		function isChanged() {
			// Normalize line endings to CRLF, like $.fn.serializeObject does.
			var newText = $text.val().replace( /\r?\n/g, '\r\n' );
			return newText !== data.wpTextbox1;
		}

		function onEditorIdle() {
			if ( !isChanged() ) {
				return;
			}

			stashEdit();
		}

		function onTextKeyUp( e ) {
			// Ignore keystrokes that don't modify text, like cursor movements.
			// See <http://www.javascripter.net/faq/keycodes.htm> and
			// <http://www.quirksmode.org/js/keys.html>. We don't have to be
			// exhaustive, because the cost of misfiring is low.
			if ( ( e.which >= 33 && e.which <= 40 ) || ( e.which >= 16 && e.which <= 18 ) ) {
				return;
			}

			clearTimeout( timer );
			timer = setTimeout( onEditorIdle, idleTimeout );
		}

		function onFormLoaded() {
			if (
				// Reverts may involve use (undo) links; stash as they review the diff.
				// Since the form has a pre-filled summary, stash the edit immediately.
				mw.util.getParamValue( 'undo' ) !== null
				// Pressing "show changes" and "preview" also signify that the user will
				// probably save the page soon
				|| $.inArray( $form.find( '#mw-edit-mode' ).val(), [ 'preview', 'diff' ] ) > -1
			) {
				stashEdit();
			}
		}

		// We don't attempt to stash new section edits because in such cases
		// the parser output varies on the edit summary (since it determines
		// the new section's name).
		if ( $form.find( 'input[name=wpSection]' ).val() === 'new' ) {
			return;
		}

		$text.on( { change: onEditorIdle, keyup: onTextKeyUp } );
		$summary.on( { focus: onEditorIdle } );
		onFormLoaded();

	} );
}( mediaWiki, jQuery ) );
