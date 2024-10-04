/*!
 * JavaScript for Special:Undelete
 */
( function () {

	if ( mw.config.get( 'wgCanonicalSpecialPageName' ) !== 'Undelete' ) {
		return;
	}

	$( () => {
		const $widget = $( '#wpComment' ).closest( '.oo-ui-widget' );

		if ( !$widget.length ) {
			// If the user has permission to see only the deleted
			// revisions but not restore or the page is not currently
			// deleted there'd be no comment field and no checkboxes.
			return;
		}

		const wpComment = OO.ui.infuse( $widget );

		const wpCommentList = OO.ui.infuse( $( '#wpCommentList' ).closest( '.oo-ui-widget' ) );

		$( '#mw-undelete-invert' ).on( 'click', () => {
			$( '.mw-undelete-revlist input[type="checkbox"]' ).prop( 'checked', ( i, val ) => !val );
		} );

		mw.widgets.visibleCodePointLimitWithDropdown( wpComment, wpCommentList, mw.config.get( 'wgCommentCodePointLimit' ) );
	} );
}() );
