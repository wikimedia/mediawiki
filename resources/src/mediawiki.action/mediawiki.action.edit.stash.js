/*!
 * Scripts for pre-emptive edit preparing on action=edit
 */
( function ( mw, $ ) {
	$( function () {
		var idleTimeout = 4000,
			api = new mw.Api(),
			pending = null,
			$form = $( '#editform' ),
			$text = $form.find( '#wpTextbox1' ),
			data = {},
			timer = null;

		function stashEdit( token ) {
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
		}

		/* Has the edit body text changed since the last stashEdit() call? */
		function isChanged() {
			// Normalize line endings to CRLF, like $.fn.serializeObject does.
			var newText = $text.val().replace( /\r?\n/g, '\r\n' );
			return newText !== data.wpTextbox1;
		}

		function onEditChanged() {
			if ( !isChanged() ) {
				return;
			}

			// If a request is in progress, abort it; its payload is stale.
			if ( pending ) {
				pending.abort();
			}

			api.getToken( 'edit' ).then( stashEdit );
		}

		function onKeyPress( e ) {
			// Ignore keystrokes that don't modify text, like cursor movements.
			// See <http://stackoverflow.com/q/2284844>.
			if ( e.which === 0 ) {
				return;
			}

			clearTimeout( timer );

			if ( pending ) {
				pending.abort();
			}

			timer = setTimeout( onEditChanged, idleTimeout );
		}

		// We don't attempt to stash new section edits because in such cases
		// the parser output varies on the edit summary (since it determines
		// the new section's name).
		if ( $form.find( 'input[name=wpSection]' ).val() === 'new' ) {
			return;
		}

		$text.on( { change: onEditChanged, keypress: onKeyPress } );

	} );
}( mediaWiki, jQuery ) );
