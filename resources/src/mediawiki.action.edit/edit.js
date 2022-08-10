/*!
 * Scripts for action=edit as rendered by EditPage.php.
 */
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
	var $wpSummary = $( '#wpSummaryWidget' );

	// The summary field might not be there, e.g. when extensions replace it
	if ( $wpSummary.length ) {
		var wpSummary = OO.ui.infuse( $wpSummary );

		// Show a byte-counter to users with how many bytes are left for their edit summary.
		mw.widgets.visibleCodePointLimit( wpSummary, mw.config.get( 'wgCommentCodePointLimit' ) );
	}

	// Restore the edit box scroll state following a preview operation,
	// and set up a form submission handler to remember this state.
	var editBox = document.getElementById( 'wpTextbox1' );
	var scrollTop = document.getElementById( 'wpScrolltop' );
	var $editForm = $( '#editform' );
	mw.hook( 'wikipage.editform' ).fire( $editForm );
	if ( $editForm.length && editBox && scrollTop ) {
		if ( scrollTop.value ) {
			editBox.scrollTop = scrollTop.value;
		}
		$editForm.on( 'submit', function () {
			scrollTop.value = editBox.scrollTop;
		} );
	}

	mw.hook( 'wikipage.watchlistChange' ).add( function ( isWatched, expiry, expirySelected ) {
		// Update the "Watch this page" checkbox on action=edit when the
		// page is watched or unwatched via the tab (T14395).
		var watchCheckbox = document.getElementById( 'wpWatchthisWidget' );
		if ( watchCheckbox ) {
			OO.ui.infuse( watchCheckbox ).setSelected( isWatched );

			// Also reset expiry selection to keep it in sync
			if ( isWatched ) {
				var expiryCheckbox = document.getElementById( 'wpWatchlistExpiryWidget' );
				if ( expiryCheckbox ) {
					OO.ui.infuse( expiryCheckbox ).setValue( expirySelected );
				}
			}
		}
	} );
} );

require( './stash.js' );

require( './watchlistExpiry.js' );
