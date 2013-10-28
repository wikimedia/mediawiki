/*!
 * Inline confirmation for rollback links.
 */

( function ( mw, $ ) {
	$( function () {
		$( '.mw-rollback-link a' ).confirmable( {
			i18n: { confirm: mw.message( 'confirmable-confirm-rollback', mw.user ).text() },
			buttonCallback: function ( $button, which ) {
				if ( which === 'no' ) {
					return $button
						// Remove misleading attributes
						.removeAttr( 'title href' )
						// Restore keyboard accessibility after we removed the 'href'
						.attr( {
							role: 'button',
							tabindex: 0
						} );
				} else {
					return $button;
				}
			}
		} );
	} );
}( mediaWiki, jQuery ) );
