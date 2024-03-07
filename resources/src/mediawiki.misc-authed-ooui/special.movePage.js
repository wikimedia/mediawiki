/*!
 * JavaScript for Special:MovePage
 */
( function () {

	if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'Movepage' ) {
		return;
	}

	$( function () {
		var wpReason = OO.ui.infuse( $( '#wpReason' ) );

		// Infuse for pretty dropdown
		OO.ui.infuse( $( '#wpNewTitle' ) );

		var wpReasonList = OO.ui.infuse( $( '#wpReasonList' ).closest( '.oo-ui-widget' ) );

		mw.widgets.visibleCodePointLimitWithDropdown( wpReason, wpReasonList, mw.config.get( 'wgCommentCodePointLimit' ) );
	} );
}() );
