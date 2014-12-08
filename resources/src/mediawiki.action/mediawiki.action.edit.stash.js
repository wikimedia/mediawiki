/*!
 * Scripts for pre-emptive edit preparing on action=edit
 */
( function ( mw, $ ) {
	$( function () {
		var api = new mw.Api(), pending = null, $form = $( '#editform' ), currentChangeId = 0, stash = {};

		function stashEdit( token ) {
			var changeId = currentChangeId,
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

			pending.then( function ( result ) {
				// Associate the cache key with the ordinal change it represents.
				if ( result.stashedit && result.stashedit.key ) {
					stash[changeId] = result.stashedit.key;
				}
			} );

			return pending;
		}

		function onEditChanged() {
			currentChangeId++;

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

		$form.on( 'submit', function () {
			var key = stash[currentChangeId];
			if ( key ) {
				// We have a stash key, so override the submit to only include
				// the key rather than the full edit payload.
				api.FIXME();
				return false;
			} else if ( pending ) {
				// It's too late in the game for us to be waiting on any
				// in-flight requests. If there are any, abort them.
				pending.abort();
			}
		} );
	} );
}( mediaWiki, jQuery ) );
