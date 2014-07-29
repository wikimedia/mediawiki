/*!
 * Enables double-click-to-edit functionality.
 */
( function ( mw, $ ) {
	$( function () {
		mw.util.$content.dblclick( function ( e ) {
			var hookStatus = { edit: true };
			mw.hook( 'dblclickedit' ).fire( hookStatus );
			if ( hookStatus.edit ) {
				e.preventDefault();
				// Trigger native HTMLElement click instead of opening URL (bug 43052)
				$( '#ca-edit a' ).get( 0 ).click();
			}
		} );
	} );
}( mediaWiki, jQuery ) );
