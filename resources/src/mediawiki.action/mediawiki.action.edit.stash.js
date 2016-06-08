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
			autoStash = ( $form.find( 'input[name=oldid]' ).val() !== '0' ),
			data = {},
			timer = null;

		function stashEdit() {
			// If a request is in progress, abort it; its payload is stale
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
					contentmodel: data.model,
					contentformat: data.format,
					baserevid: data.parentRevId
				} );
			} );
		}

		/* Has the edit body text changed since the last stashEdit() call? */
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

		function onSummaryKeyPress( e ) {
			// Reverts may involve loading an old version and saving it with no text changes
			if ( !autoStash || e.which === 0 ) {
				return;
			}

			autoStash = false;
			stashEdit();
		}

		function onFormLoaded() {
			// Reverts may involve use (undo) links; stash as they review the diff
			if ( mw.util.getParamValue( 'undo' ) !== null ) {
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
		$summary.on( { keypress: onSummaryKeyPress } );
		$( onFormLoaded );

	} );
}( mediaWiki, jQuery ) );
