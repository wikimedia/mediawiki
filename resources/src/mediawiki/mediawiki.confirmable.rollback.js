/*!
 * Inline confirmation for rollback links.
 */
( function ( mw, $ ) {
	$( function () {
		$( '.mw-rollback-link a' ).confirmable( {
			i18n: {
				confirm: mw.message( 'confirmable-confirm-rollback', mw.user ).text()
			}
		} );
	} );
}( mediaWiki, jQuery ) );
