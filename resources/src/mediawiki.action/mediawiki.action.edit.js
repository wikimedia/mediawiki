/*!
 * Scripts for action=edit at domready
 */
( function ( mw, $ ) {
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
		var editBox, scrollTop, $editForm;

		if ( $( '#editform' ).hasClass( 'mw-editform-ooui' ) ) {
			mw.loader.using( 'oojs-ui-core' ).then( function () {
				var wpSummary = OO.ui.infuse( $( '#wpSummaryWidget' ) );

				// Restore appropriate modifier keys for the accesskey in the 'title' attribute
				// TODO: This should be an OOjs UI feature, or somehow happen automatically after infusing.
				wpSummary.$input.updateTooltipAccessKeys();

				// Make sure edit summary does not exceed byte limit
				wpSummary.$input.byteLimit( 255 );

				// Show a byte-counter to users with how many bytes are left for their edit summary.
				// TODO: This looks a bit weird, as there is no unit in the UI, just numbers; showing
				// 'bytes' confused users in testing, and showing 'chars' would be a lie. See T42035.
				function updateSummaryLabelCount() {
					wpSummary.setLabel( String( 255 - $.byteLength( wpSummary.getValue() ) ) );
				}
				wpSummary.on( 'change', updateSummaryLabelCount );
				// Initialise value
				updateSummaryLabelCount();
			} );
		} else {
			// Make sure edit summary does not exceed byte limit
			$( '#wpSummary' ).byteLimit( 255 );
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
			$editForm.submit( function () {
				scrollTop.value = editBox.scrollTop;
			} );
		}
	} );
}( mediaWiki, jQuery ) );
