/**
 * Add classes to the undo button to 1. Display if the user is logged in and
 * 2. Change the styling of the undo link into a Codex fake button
 *
 * @param $undoButton
 */
module.exports = function ( $undoButton ) {
	const UNDO_LINK_FAKE_BUTTON_CLASS =
		'cdx-button cdx-button--fake-button cdx-button--fake-button--enabled cdx-button--action-default';
	const isMinerva = mw.config.get( 'skin' ) === 'minerva';
	if ( isMinerva ) {
		if ( mw.user.isAnon() ) {
			$undoButton.hide();
		}
		const $rollbackButton = $( '.mw-rollback-link' );
		// eslint-disable-next-line mediawiki/class-doc
		$undoButton.children( 'a' ).addClass( UNDO_LINK_FAKE_BUTTON_CLASS );
		// eslint-disable-next-line mediawiki/class-doc
		$rollbackButton.children( 'a' ).addClass( UNDO_LINK_FAKE_BUTTON_CLASS );
	}
};
