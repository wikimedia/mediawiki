/*!
 * Scripts for pre-emptive edit preparing on action=edit
 */
( function ( mw, $ ) {
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

		function onTextChanged() {
			if ( !isChanged() ) {
				return;
			}

			stashEdit();
		}

		function onTextKeyPress( e ) {
			// Ignore keystrokes that don't modify text, like cursor movements.
			// See <http://stackoverflow.com/q/2284844>.
			if ( e.which === 0 ) {
				return;
			}

			clearTimeout( timer );

			if ( pending ) {
				pending.abort();
			}

			timer = setTimeout( onTextChanged, idleTimeout );
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

		$text.on( { change: onTextChanged, keypress: onTextKeyPress } );
		$summary.on( { focus: onTextChanged } );
		onFormLoaded();

	} );
}( mediaWiki, jQuery ) );
