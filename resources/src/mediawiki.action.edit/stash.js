/*!
 * Pre-emptive page parsing edits.
 *
 * See also PageEditStash in PHP.
 */
$( () => {
	const PRIORITY_LOW = 1;
	const PRIORITY_HIGH = 2;

	// Do not attempt to stash "new section" edits, because for those cases the ParserOutput
	// varies on the edit summary field, and thus pre-parsing the page whilst that field is
	// being typed in would be counter-productive. (The field is re-purposed for the new
	// section's heading.)
	const $form = $( '#editform' );
	const section = $form.find( '[name=wpSection]' ).val();
	if ( !$form.length || section === 'new' ) {
		return;
	}

	let lastText, lastSummary, lastTextHash;
	let lastPriority = 0;
	const $text = $form.find( '#wpTextbox1' );
	const $summary = $form.find( '#wpSummary' );
	const model = $form.find( '[name=model]' ).val();
	const format = $form.find( '[name=format]' ).val();
	const revId = $form.find( '[name=parentRevId]' ).val();

	// Whether the body text content changed since the last stashEdit()
	function isTextChanged() {
		return lastText !== $text.textSelection( 'getContents' );
	}

	// Whether the edit summary has changed since the last stashEdit()
	function isSummaryChanged() {
		return lastSummary !== $summary.textSelection( 'getContents' );
	}

	// Send a request to stash the edit to the API.
	// If a request is in progress, abort it since its payload is stale and the API
	// may limit concurrent stash parses.
	const api = new mw.Api();
	let stashReq;
	function stashEdit() {
		const textChanged = isTextChanged();
		const priority = textChanged ? PRIORITY_HIGH : PRIORITY_LOW;

		if ( stashReq ) {
			if ( lastPriority > priority ) {
				// Stash request for summary change should wait on pending text change stash
				stashReq.then( checkStash );
				return;
			}
			stashReq.abort();
		}

		// Update the "last" tracking variables
		lastSummary = $summary.textSelection( 'getContents' );
		lastPriority = priority;
		if ( textChanged ) {
			lastText = $text.textSelection( 'getContents' );
			// Reset hash
			lastTextHash = null;
		}

		const params = {
			formatversion: 2,
			action: 'stashedit',
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

		const req = api.postWithToken( 'csrf', params );
		stashReq = req;
		req.then( ( data ) => {
			if ( req === stashReq ) {
				stashReq = null;
			}
			if ( data.stashedit && data.stashedit.texthash ) {
				lastTextHash = data.stashedit.texthash;
			} else {
				// Request failed or text hash expired;
				// include the text in a future stash request.
				lastTextHash = null;
			}
		} );
	}

	// Check whether text or summary have changed and call stashEdit()
	function checkStash() {
		if ( !isTextChanged() && !isSummaryChanged() ) {
			return;
		}

		stashEdit();
	}

	let idleTimeout = 3000;
	let timer;
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

	$text.on( {
		keyup: onKeyUp,
		focus: onTextFocus,
		change: checkStash
	} );
	$summary.on( {
		keyup: onKeyUp,
		focus: onSummaryFocus,
		focusout: checkStash
	} );

	if (
		// Reverts may involve use (undo) links; stash as they review the diff.
		// Since the form has a pre-filled summary, stash the edit immediately.
		mw.util.getParamValue( 'undo' ) !== null ||
		// Pressing "show changes" and "preview" also signify that the user will
		// probably save the page soon
		[ 'preview', 'diff' ].indexOf( $form.find( '#mw-edit-mode' ).val() ) > -1
	) {
		checkStash();
	}
} );
