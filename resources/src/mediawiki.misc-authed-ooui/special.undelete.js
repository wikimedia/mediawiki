/*!
 * JavaScript for Special:Undelete
 */
( function () {

	if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'Undelete' ) {
		return;
	}

	$( () => {
		var $widget = $( '#wpComment' ).closest( '.oo-ui-widget' ),
			wpComment;

		if ( !$widget.length ) {
			// If the user has permission to see only the deleted
			// revisions but not restore or the page is not currently
			// deleted there'd be no comment field and no checkboxes.
			return;
		}

		wpComment = OO.ui.infuse( $widget );

		var wpCommentList = OO.ui.infuse( $( '#wpCommentList' ).closest( '.oo-ui-widget' ) );

		$( '#mw-undelete-invert' ).on( 'click', () => {
			$( '.mw-undelete-revlist input[type="checkbox"]' ).prop( 'checked', ( i, val ) => !val );
		} );

		mw.widgets.visibleCodePointLimitWithDropdown( wpComment, wpCommentList, mw.config.get( 'wgCommentCodePointLimit' ) );
	} );
}() );
