/*!
 * Enables double-click-to-edit functionality.
 */
( function () {
	$( function () {
		mw.util.$content.on( 'dblclick', function ( e ) {
			var $a;
			// Recheck preference so extensions can do a hack to disable this code.
			if ( parseInt( mw.user.options.get( 'editondblclick' ), 10 ) ) {
				// Trigger native HTMLElement click instead of opening URL (T45052)
				$a = $( '#ca-edit a' );
				// Not every page has an edit link (T59713)
				if ( $a.length ) {
					e.preventDefault();
					// eslint-disable-next-line no-jquery/no-event-shorthand
					$a.get( 0 ).click();
				}
			}
		} );
	} );
}() );
