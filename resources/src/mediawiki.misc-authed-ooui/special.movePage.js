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

		var colonSeparator = mw.msg( 'colon-separator' ),
			wpReasonList = OO.ui.infuse( $( '#wpReasonList' ).closest( '.oo-ui-widget' ) ),
			filterFunction = function ( input ) {
				// Should be built the same as in SpecialMovePage::execute()
				var comment = wpReasonList.getValue();
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
		wpReasonList.on( 'change', function () {
			wpReason.emit( 'change' );
		} );
	} );
}() );
