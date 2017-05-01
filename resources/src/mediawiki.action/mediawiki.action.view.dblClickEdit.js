/*!
 * Enables double-click-to-edit functionality.
 */
( function ( mw, $ ) {
	$( function () {
		mw.util.$content.dblclick( function ( e ) {
			// Recheck preference so extensions can do a hack to disable this code.
			if ( parseInt( mw.user.options.get( 'editondblclick' ), 10 ) ) {
				e.preventDefault();
				// Trigger native HTMLElement click instead of opening URL (bug 43052)
				var $a = $( '#ca-edit a' );
				// Not every page has an edit link (bug 57713)
				if ( $a.length ) {
					$a.get( 0 ).click();
				}
			}
		} );
	} );
}( mediaWiki, jQuery ) );
