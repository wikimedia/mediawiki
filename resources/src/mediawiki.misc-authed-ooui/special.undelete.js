/*!
 * JavaScript for Special:Undelete
 */
( function () {

	if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'Undelete' ) {
		return;
	}

	$( function () {
		var $widget = $( '#wpComment' ).closest( '.oo-ui-widget' ),
			wpComment;

		if ( !$widget.length ) {
			// If the user has permission to see only the deleted
			// revisions but not restore or the page is not currently
			// deleted there'd be no comment field and no checkboxes.
			return;
		}

		wpComment = OO.ui.infuse( $widget );

		var colonSeparator = mw.msg( 'colon-separator' ),
			wpCommentList = OO.ui.infuse( $( '#wpCommentList' ).closest( '.oo-ui-widget' ) ),
			filterFunction = function ( input ) {
				// Should be built the same as in SpecialUndelete::loadRequest()
				var comment = wpCommentList.getValue();
				if ( comment === 'other' ) {
					comment = input;
				} else if ( input !== '' ) {
					// Entry from drop down menu + additional comment
					comment += colonSeparator + input;
				}
				return comment;
			};

		$( '#mw-undelete-invert' ).on( 'click', function () {
			$( '.mw-undelete-revlist input[type="checkbox"]' ).prop( 'checked', function ( i, val ) {
				return !val;
			} );
		} );

		mw.widgets.visibleCodePointLimit( wpComment, mw.config.get( 'wgCommentCodePointLimit' ), filterFunction );
		// Keep the remaining counter in sync when reason list changed
		wpCommentList.on( 'change', function () {
			wpComment.emit( 'change' );
		} );
	} );
}() );
