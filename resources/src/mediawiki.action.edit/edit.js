/*!
 * Scripts for action=edit as rendered by EditPage.php.
 */
'use strict';

/**
 * Fired when the editform is added to the edit page.
 *
 * Similar to the {@link event:'wikipage.content' wikipage.content hook},
 * $editForm can still be detached when this hook is fired.
 *
 * @event ~'wikipage.editform'
 * @memberof Hooks
 * @param {jQuery} $editForm The most appropriate element containing the
 *   editform, usually #editform.
 */

$( () => {
	const $wpSummary = $( '#wpSummaryWidget' );

	// The summary field might not be there, e.g. when extensions replace it
	if ( $wpSummary.length ) {
		const wpSummary = OO.ui.infuse( $wpSummary );

		// Show a byte-counter to users with how many bytes are left for their edit summary.
		mw.widgets.visibleCodePointLimit( wpSummary, mw.config.get( 'wgCommentCodePointLimit' ) );
	}

	// Restore the edit box scroll state following a preview operation,
	// and set up a form submission handler to remember this state.
	const editBox = document.getElementById( 'wpTextbox1' );
	const scrollTop = document.getElementById( 'wpScrolltop' );
	const $editForm = $( '#editform' );
	mw.hook( 'wikipage.editform' ).fire( $editForm );
	if ( $editForm.length && editBox && scrollTop ) {
		if ( scrollTop.value ) {
			editBox.scrollTop = scrollTop.value;
		}
		$editForm.on( 'submit', () => {
			scrollTop.value = editBox.scrollTop;
		} );
	}

	mw.hook( 'wikipage.watchlistChange' ).add( ( isWatched, expiry, expirySelected ) => {
		// Update the "Watch this page" checkbox on action=edit when the
		// page is watched or unwatched via the tab (T14395).
		const watchCheckbox = document.getElementById( 'wpWatchthisWidget' );
		if ( watchCheckbox ) {
			OO.ui.infuse( watchCheckbox ).setSelected( isWatched );

			// Also reset expiry selection to keep it in sync
			if ( isWatched ) {
				const expiryCheckbox = document.getElementById( 'wpWatchlistExpiryWidget' );
				if ( expiryCheckbox ) {
					OO.ui.infuse( expiryCheckbox ).setValue( expirySelected );
				}
			}
		}
	} );
} );

require( './stash.js' );

require( './watchlistExpiry.js' );
