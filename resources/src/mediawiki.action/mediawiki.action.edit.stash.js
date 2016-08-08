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
			timer,
			pending,
			lastText,
			lastSummary,
			lastTextHash,
			$form = $( '#editform' ),
			$text = $form.find( '#wpTextbox1' ),
			$summary = $form.find( '#wpSummary' ),
			section = $form.find( '[name=wpSection]' ).val(),
			model = $form.find( '[name=model]' ).val(),
			format = $form.find( '[name=format]' ).val(),
			revId = $form.find( '[name=parentRevId]' ).val(),
			lastPriority = 0,
			PRIORITY_LOW = 1,
			PRIORITY_HIGH = 2;

		// Send a request to stash the edit to the API.
		// If a request is in progress, abort it since its payload is stale and the API
		// may limit concurrent stash parses.
		function stashEdit() {
			api.getToken( 'csrf' ).then( function ( token ) {
				var req, params,
					textChanged = isTextChanged(),
					priority = textChanged ? PRIORITY_HIGH : PRIORITY_LOW;

				if ( pending ) {
					if ( lastPriority > priority ) {
						// Stash request for summary change should wait on pending text change stash
						pending.then( checkStash );
						return;
					}
					pending.abort();
				}

				// Update the "last" tracking variables
				lastSummary = $summary.textSelection( 'getContents' );
				lastPriority = priority;
				if ( textChanged ) {
					lastText = $text.textSelection( 'getContents' );
					// Reset hash
					lastTextHash = null;
				}

				params = {
					action: 'stashedit',
					token: token,
					title: mw.config.get( 'wgPageName' ),
					section: section,
					sectiontitle: '',
					summary: lastSummary,
					contentmodel: model,
					contentformat: format,
					baserevid: revId
				};
				if ( lastTextHash ) {
					params.stashedtexthash = lastTextHash;
				} else {
					params.text = lastText;
				}

				req = api.post( params );
				pending = req;
				req.then( function ( data ) {
					if ( req === pending ) {
						pending = null;
					}
					if ( data.stashedit && data.stashedit.texthash ) {
						lastTextHash = data.stashedit.texthash;
					} else {
						// Request failed or text hash expired;
						// include the text in a future stash request.
						lastTextHash = null;
					}
				} );
			} );
		}

		// Whether the body text content changed since the last stashEdit()
		function isTextChanged() {
			return lastText !== $text.textSelection( 'getContents' );
		}

		// Whether the edit summary has changed since the last stashEdit()
		function isSummaryChanged() {
			return lastSummary !== $summary.textSelection( 'getContents' );
		}

		// Check whether text or summary have changed and call stashEdit()
		function checkStash() {
			if ( !isTextChanged() && !isSummaryChanged() ) {
				return;
			}

			stashEdit();
		}

		function onKeyUp( e ) {
			// Ignore keystrokes that don't modify text, like cursor movements.
			// See <http://www.javascripter.net/faq/keycodes.htm> and
			// <http://www.quirksmode.org/js/keys.html>. We don't have to be exhaustive,
			// because the cost of misfiring is low.
			// * Key code 33-40: Page Up/Down, End, Home, arrow keys.
			// * Key code 16-18: Shift, Ctrl, Alt.
			if ( ( e.which >= 33 && e.which <= 40 ) || ( e.which >= 16 && e.which <= 18 ) ) {
				return;
			}

			clearTimeout( timer );
			timer = setTimeout( checkStash, idleTimeout );
		}

		function onSummaryFocus() {
			// Summary typing is usually near the end of the workflow and involves less pausing.
			// Re-stash more frequently in hopes of capturing the final summary before submission.
			idleTimeout = 1000;
			// Stash now since the text is likely the final version. The re-stashes based on the
			// summary are targeted at caching edit checks that need the final summary.
			checkStash();
		}

		function onTextFocus() {
			// User returned to the text field... reset stash rate to default
			idleTimeout = 3000;
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
				checkStash();
			}
		}

		// We don't attempt to stash new section edits because in such cases the parser output
		// varies on the edit summary (since it determines the new section's name).
		if ( $form.find( 'input[name=wpSection]' ).val() === 'new' ) {
			return;
		}

		$text.on( {
			change: checkStash,
			keyup: onKeyUp,
			focus: onTextFocus
		} );
		$summary.on( {
			focus: onSummaryFocus,
			focusout: checkStash,
			keyup: onKeyUp
		} );
		onFormLoaded();
	} );
}( mediaWiki, jQuery ) );
