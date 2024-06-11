/*!
 * Enable double-click-to-edit functionality.
 */
( function () {
	if ( Number( mw.user.options.get( 'editondblclick' ) ) !== 1 ) {
		// Support both 1 or "1" (T54542)
		return;
	}

	if ( mw.config.get( 'wgAction' ) !== 'view' ) {
		// Only trigger during view action.
		return;
	}

	$( () => {
		$( '#mw-content-text' ).on( 'dblclick', ( e ) => {
			// Trigger native HTMLElement click instead of opening URL (T45052)
			const $a = $( '#ca-edit a' );
			// Not every page has an edit link (T59713)
			if ( $a.length ) {
				e.preventDefault();
				$a.get( 0 ).click();
			}
		} );
	} );
}() );
