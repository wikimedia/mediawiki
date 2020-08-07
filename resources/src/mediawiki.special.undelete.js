/*!
 * JavaScript for Special:Undelete
 */
( function () {
	$( function () {
		var summaryCodePointLimit = mw.config.get( 'wgCommentCodePointLimit' ),
			summaryByteLimit = mw.config.get( 'wgCommentByteLimit' ),
			$widget = $( '#wpComment' ).closest( '.oo-ui-widget' ),
			wpComment;

		if ( !$widget.length ) {
			// If the user has permission to see only the deleted
			// revisions but not restore or the page is not currently
			// deleted there'd be no comment field and no checkboxes.
			return;
		}

		wpComment = OO.ui.infuse( $widget );

		$( '#mw-undelete-invert' ).on( 'click', function () {
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
}() );
