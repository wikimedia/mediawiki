/*!
 * Javascript for action=delete and Special:RevisionDelete at domready
 */
( function () {
	$( function () {
		var colonSeparator = mw.message( 'colon-separator' ).text(),
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

		reason.$input.codePointLimit( mw.config.get( 'wgCommentCodePointLimit' ), filterFunction );
	} );
}() );
