/*!
 * JavaScript for Special:RevisionDelete
 */
( function () {
	if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'Revisiondelete' ) {
		return;
	}

	var colonSeparator = mw.msg( 'colon-separator' ),
		wpRevDeleteReasonList = OO.ui.infuse( $( '#wpRevDeleteReasonList' ) ),
		wpReason = OO.ui.infuse( $( '#wpReason' ) ),
		filterFunction = function ( input ) {
			// Should be built the same as in SpecialRevisionDelete::submit()
			var comment = wpRevDeleteReasonList.getValue();
			if ( comment === 'other' ) {
				comment = input;
			} else if ( input !== '' ) {
				// Entry from drop down menu + additional comment
				comment += colonSeparator + input;
			}
			return comment;
		};

	mw.widgets.visibleCodePointLimit( wpReason, mw.config.get( 'wgCommentCodePointLimit' ), filterFunction );
	// Keep the remaining counter in sync when reason list changed
	wpRevDeleteReasonList.on( 'change', function () {
		wpReason.emit( 'change' );
	} );
}() );
