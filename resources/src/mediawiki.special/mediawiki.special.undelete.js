/*!
 * JavaScript for Special:Undelete
 */
( function ( mw, $ ) {
	$( function () {
		var summaryCodePointLimit = mw.config.get( 'wgCommentCodePointLimit' ),
			summaryByteLimit = mw.config.get( 'wgCommentByteLimit' ),
			wpComment = OO.ui.infuse( $( '#wpComment' ).closest( '.oo-ui-widget' ) );

		$( '#mw-undelete-invert' ).click( function () {
			$( '.mw-undelete-revlist input[type="checkbox"]' ).prop( 'checked', function ( i, val ) {
				return !val;
			} );
		} );

		// Limit to bytes or UTF-8 codepoints, depending on MediaWiki's configuration
		if ( summaryCodePointLimit ) {
			mw.widgets.visibleCodePointLimit( wpComment, summaryCodePointLimit );
		} else if ( summaryByteLimit ) {
			mw.widgets.visibleByteLimit( wpComment, summaryByteLimit );
		}
	} );
}( mediaWiki, jQuery ) );
