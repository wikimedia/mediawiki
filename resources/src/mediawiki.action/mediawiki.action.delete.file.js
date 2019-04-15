/*!
 * JavaScript for Special:RevisionDelete
 */
( function () {
	$( function () {
		var colonSeparator = mw.message( 'colon-separator' ).text(),
			summaryCodePointLimit = mw.config.get( 'wgCommentCodePointLimit' ),
			summaryByteLimit = mw.config.get( 'wgCommentByteLimit' ),
			reasonList = OO.ui.infuse( $( '#wpDeleteReasonList' ).closest( '.oo-ui-widget' ) ),
			reason = OO.ui.infuse( $( '#wpReason' ).closest( '.oo-ui-widget' ) ),
			filterFunction = function ( input ) {
				// Should be built the same as in SpecialRevisionDelete::submit()
				var comment = reasonList.getValue();
				if ( comment === 'other' ) {
					comment = input;
				} else if ( input !== '' ) {
					// Entry from drop down menu + additional comment
					comment += colonSeparator + input;
				}
				return comment;
			};

		// Limit to bytes or UTF-8 codepoints, depending on MediaWiki's configuration
		if ( summaryCodePointLimit ) {
			reason.$input.codePointLimit( summaryCodePointLimit, filterFunction );
		} else if ( summaryByteLimit ) {
			reason.$input.byteLimit( summaryByteLimit, filterFunction );
		}
	} );

}() );
