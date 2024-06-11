/*!
 * JavaScript for action=delete
 */
( function () {
	if ( mw.config.get( 'wgAction' ) !== 'delete' ) {
		return;
	}

	$( () => {
		var reasonList = OO.ui.infuse( $( '#wpDeleteReasonList' ) ),
			reason = OO.ui.infuse( $( '#wpReason' ) );

		mw.widgets.visibleCodePointLimitWithDropdown( reason, reasonList, mw.config.get( 'wgCommentCodePointLimit' ) );
	} );
}() );
