/*!
 * JavaScript for action=delete
 */
( function () {
	if ( mw.config.get( 'wgAction' ) !== 'delete' ) {
		return;
	}

	$( function () {
		var colonSeparator = mw.msg( 'colon-separator' ),
			reasonList = OO.ui.infuse( $( '#wpDeleteReasonList' ).closest( '.oo-ui-widget' ) ),
			reason = OO.ui.infuse( $( '#wpReason' ).closest( '.oo-ui-widget' ) ),
			filterFunction = function ( input ) {
				// Should be built the same as in Article::delete()
				// and SpecialRevisionDelete::submit()
				var comment = reasonList.getValue();
				if ( comment === 'other' ) {
					comment = input;
				} else if ( input !== '' ) {
					// Entry from drop down menu + additional comment
					comment += colonSeparator + input;
				}
				return comment;
			};

		mw.widgets.visibleCodePointLimit( reason, mw.config.get( 'wgCommentCodePointLimit' ), filterFunction );
		// Keep the remaining counter in sync when reason list changed
		reasonList.on( 'change', function () {
			reason.emit( 'change' );
		} );
	} );
}() );
