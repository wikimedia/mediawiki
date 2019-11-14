/*!
 * Enable double-click-to-edit functionality.
 */
( function () {
	if ( !parseInt( mw.user.options.get( 'editondblclick' ), 10 ) ) {
		return;
	}
	$( function () {
		$( '#mw-content-text' ).on( 'dblclick', function ( e ) {
			// Trigger native HTMLElement click instead of opening URL (T45052)
			var $a = $( '#ca-edit a' );
			// Not every page has an edit link (T59713)
			if ( $a.length ) {
				e.preventDefault();
				$a.get( 0 ).click();
			}
		} );
	} );
}() );
