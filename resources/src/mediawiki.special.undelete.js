/*!
 * JavaScript for Special:Undelete
 */
( function () {
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

		$( '#mw-undelete-invert' ).on( 'click', function () {
			$( '.mw-undelete-revlist input[type="checkbox"]' ).prop( 'checked', function ( i, val ) {
				return !val;
			} );
		} );

		mw.widgets.visibleCodePointLimit( wpComment, mw.config.get( 'wgCommentCodePointLimit' ) );
	} );
}() );
