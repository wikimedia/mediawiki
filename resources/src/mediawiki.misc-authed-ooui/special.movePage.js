/*!
 * JavaScript for Special:MovePage
 */
( function () {

	if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'Movepage' ) {
		return;
	}

	$( () => {
		const wpReason = OO.ui.infuse( $( '#wpReason' ) );

		// Infuse for pretty dropdown
		OO.ui.infuse( $( '#wpNewTitle' ) );

		const wpReasonList = OO.ui.infuse( $( '#wpReasonList' ).closest( '.oo-ui-widget' ) );

		mw.widgets.visibleCodePointLimitWithDropdown( wpReason, wpReasonList, mw.config.get( 'wgCommentCodePointLimit' ) );
	} );
}() );
