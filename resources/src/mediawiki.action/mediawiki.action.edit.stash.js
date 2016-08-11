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
			section = $form.find( '[name=wpSection]' ).val(),
			model = $form.find( '[name=model]' ).val(),
			format = $form.find( '[name=format]' ).val(),
			revId = $form.find( '[name=parentRevId]' ).val(),
			lastText = $text.textSelection( 'getContents' ),
			lastSummary = $summary.textSelection( 'getContents' ),
			lastTextHash = null,
			lastPriority = 0,
			origSummary = lastSummary,
			timer = null,
			PRIORITY_LOW = 1,
			PRIORITY_HIGH = 2;

		// Send a request to stash the edit to the API.
		// If a request is in progress, abort it since its payload is stale and the API
		// may limit concurrent stash parses.
		function stashEdit( priority, hashForReuse ) {
			if ( pending ) {
				pending.abort();
				pending = null;
			}

			api.getToken( 'csrf' ).then( function ( token ) {
				// If applicable, just send the hash key to reuse the last text server-side
				var req, params;

				// Update tracking of the last text/summary sent out
				lastText = $text.textSelection( 'getContents' );
				lastSummary = $summary.textSelection( 'getContents' );
				lastPriority = priority;
				lastTextHash = null; // "failed" until proven successful

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
				if ( hashForReuse ) {
					params.stashedtexthash = hashForReuse;
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
					}
				} );
			} );
		}

		// Check if edit body text changed since the last stashEdit() call or if no edit
		// stash calls have yet been made
		function isTextChanged() {
			var newText = $text.textSelection( 'getContents' );
			return newText !== lastText;
		}

		// Check if summary changed since the last stashEdit() call or if no edit
		// stash calls have yet been made
		function isSummaryChanged() {
			var newSummary = $summary.textSelection( 'getContents' );
			return newSummary !== lastSummary;
		}

		function onEditorIdle() {
			var textChanged = isTextChanged(),
				summaryChanged = isSummaryChanged(),
				priority = textChanged ? PRIORITY_HIGH : PRIORITY_LOW;

			if ( !textChanged && !summaryChanged ) {
				return; // nothing to do
			}

			if ( pending && lastPriority > priority ) {
				// Stash requests for summary changes should wait on pending text change stashes
				pending.then( onEditorIdle );
				return;
			}

			stashEdit( priority, textChanged ? null : lastTextHash );
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
			timer = setTimeout( onEditorIdle, idleTimeout );
		}

		function onSummaryFocus() {
			// Summary typing is usually near the end of the workflow and involves less pausing.
			// Re-stash frequently in hopes of capturing the final summary before submission.
			idleTimeout = 1000;
			// Stash now since the text is likely the final version. The re-stashes based on the
			// summary are targeted at caching edit checks that need the final summary.
			onEditorIdle();
		}

		function onTextFocus() {
			// User returned to the text field...
			if ( $summary.textSelection( 'getContents' ) === origSummary ) {
				idleTimeout = 3000; // no summary yet; reset stash rate to default
			}
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
				stashEdit( PRIORITY_HIGH, null );
			}
		}

		// We don't attempt to stash new section edits because in such cases the parser output
		// varies on the edit summary (since it determines the new section's name).
		if ( $form.find( 'input[name=wpSection]' ).val() === 'new' ) {
			return;
		}

		$text.on( {
			change: onEditorIdle,
			keyup: onKeyUp,
			focus: onTextFocus
		} );
		$summary.on( {
			focus: onSummaryFocus,
			focusout: onEditorIdle,
			keyup: onKeyUp
		} );
		onFormLoaded();
	} );
}( mediaWiki, jQuery ) );
