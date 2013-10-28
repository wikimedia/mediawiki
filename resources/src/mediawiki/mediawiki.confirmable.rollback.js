/*!
 * Inline confirmation for rollback links.
 */

( function ( mw, $ ) {
	$( function () {
		$( '.mw-rollback-link a' ).confirmable( {
			i18n: { confirm: mw.message( 'confirmable-confirm-rollback', mw.user ).text() },
			buttonCallback: function ( $button, which ) {
				if ( which === 'no' ) {
					// Remove misleading attributes
					return $button.attr( {
						title: null,
						href: '#'
					} );
				} else {
					return $button;
				}
			}
		} );
	} );
}( mediaWiki, jQuery ) );
