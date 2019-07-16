/*!
 * JavaScript for Special:MovePage
 */
( function () {

	if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'Movepage' ) {
		return;
	}

	$( function () {
		var summaryCodePointLimit = mw.config.get( 'wgCommentCodePointLimit' ),
			summaryByteLimit = mw.config.get( 'wgCommentByteLimit' ),
			wpReason = OO.ui.infuse( $( '#wpReason' ) );

		// Infuse for pretty dropdown
		OO.ui.infuse( $( '#wpNewTitle' ) );
		// Limit to bytes or UTF-8 codepoints, depending on MediaWiki's configuration
		if ( summaryCodePointLimit ) {
			mw.widgets.visibleCodePointLimit( wpReason, summaryCodePointLimit );
		} else if ( summaryByteLimit ) {
			mw.widgets.visibleByteLimit( wpReason, summaryByteLimit );
		}
	} );
}() );
