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

		mw.widgets.visibleCodePointLimit( wpReason, mw.config.get( 'wgCommentCodePointLimit' ) );
	} );
}() );
