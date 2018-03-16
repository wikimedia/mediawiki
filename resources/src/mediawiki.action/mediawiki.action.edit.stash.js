/*!
 * Scripts for pre-emptive edit preparing on action=edit
 */
/* eslint-disable no-use-before-define */
( function ( mw, $ ) {
	if ( !mw.config.get( 'wgAjaxEditStash' ) ) {
		return;
	}

	$( function () {
		var idleTimeout = 3000,
			api = new mw.Api(),
			timer,
			$form = $( '#editform' ),
			$text = $form.find( '#wpTextbox1' ),
			$summary = $form.find( '#wpSummary' ),
			params = {
				section: $form.find( '[name=wpSection]' ).val(),
				sectiontitle: '',
				contentmodel: $form.find( '[name=model]' ).val(),
				contentformat: $form.find( '[name=format]' ).val(),
				baserevid: $form.find( '[name=parentRevId]' ).val()
			};

		if ( !$form.length ) {
			return;
		}

		function stashEdit() {
			api.stashEdit(
				$text.textSelection( 'getContents' ),
				$summary.textSelection( 'getContents' ),
				params
			);
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
			timer = setTimeout( stashEdit, idleTimeout );
		}

		function onSummaryFocus() {
			// Summary typing is usually near the end of the workflow and involves less pausing.
			// Re-stash more frequently in hopes of capturing the final summary before submission.
			idleTimeout = 1000;
			// Stash now since the text is likely the final version. The re-stashes based on the
			// summary are targeted at caching edit checks that need the final summary.
			stashEdit();
		}

		function onTextFocus() {
			// User returned to the text field... reset stash rate to default
			idleTimeout = 3000;
		}

		$text.on( {
			keyup: onKeyUp,
			focus: onTextFocus,
			change: stashEdit
		} );
		$summary.on( {
			keyup: onKeyUp,
			focus: onSummaryFocus,
			focusout: stashEdit
		} );

		if (
			// Reverts may involve use (undo) links; stash as they review the diff.
			// Since the form has a pre-filled summary, stash the edit immediately.
			mw.util.getParamValue( 'undo' ) !== null ||
			// Pressing "show changes" and "preview" also signify that the user will
			// probably save the page soon
			[ 'preview', 'diff' ].indexOf( $form.find( '#mw-edit-mode' ).val() ) > -1
		) {
			stashEdit();
		}
	} );
}( mediaWiki, jQuery ) );
