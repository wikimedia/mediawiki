/*!
 * Scripts for action=edit at domready
 */
( function () {
	'use strict';

	/**
	 * Fired when the editform is added to the edit page
	 *
	 * Similar to the {@link mw.hook#event-wikipage_content wikipage.content hook}
	 * $editForm can still be detached when this hook is fired.
	 *
	 * @event wikipage_editform
	 * @member mw.hook
	 * @param {jQuery} $editForm The most appropriate element containing the
	 *   editform, usually #editform.
	 */

	$( function () {
		var wpSummary, editBox, scrollTop, $editForm,
			summaryCodePointLimit = mw.config.get( 'wgCommentCodePointLimit' ),
			summaryByteLimit = mw.config.get( 'wgCommentByteLimit' ),
			$wpSummary = $( '#wpSummaryWidget' );

		// The summary field might not be there, e.g. when extensions replace it
		if ( $wpSummary.length ) {
			wpSummary = OO.ui.infuse( $wpSummary );

			// Show a byte-counter to users with how many bytes are left for their edit summary.
			// TODO: This looks a bit weird, as there is no unit in the UI, just numbers; showing
			// 'bytes' confused users in testing, and showing 'chars' would be a lie. See T42035.
			// (Showing 'chars' is still confusing with the code point limit, since it's not obvious
			// that e.g. combining diacritics or zero-width punctuation count as characters.)
			if ( summaryCodePointLimit ) {
				mw.widgets.visibleCodePointLimit( wpSummary, summaryCodePointLimit );
			} else if ( summaryByteLimit ) {
				mw.widgets.visibleByteLimit( wpSummary, summaryByteLimit );
			}
		}

		// Restore the edit box scroll state following a preview operation,
		// and set up a form submission handler to remember this state.
		editBox = document.getElementById( 'wpTextbox1' );
		scrollTop = document.getElementById( 'wpScrolltop' );
		$editForm = $( '#editform' );
		mw.hook( 'wikipage.editform' ).fire( $editForm );
		if ( $editForm.length && editBox && scrollTop ) {
			if ( scrollTop.value ) {
				editBox.scrollTop = scrollTop.value;
			}
			$editForm.on( 'submit', function () {
				scrollTop.value = editBox.scrollTop;
			} );
		}
	} );
}() );
