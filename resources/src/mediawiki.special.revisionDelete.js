/*!
 * JavaScript for Special:RevisionDelete
 */
( function () {
	var colonSeparator = mw.msg( 'colon-separator' ),
		wpRevDeleteReasonList = OO.ui.infuse( '#wpRevDeleteReasonList' ),
		$wpReason = $( '#wpReason input' ),
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

	$wpReason.codePointLimit( mw.config.get( 'wgCommentCodePointLimit' ), filterFunction );

}() );
