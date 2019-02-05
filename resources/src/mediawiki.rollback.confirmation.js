/*!
 * JavaScript for rollback confirmation prompt
 */
$( function () {
	$( '.mw-rollback-link a' ).each( function () {
		$( this ).confirmable( {
			i18n: {
				confirm: mw.msg( 'rollback-confirmation-confirm', $( this ).data( 'rollback-count' ) ),
				yes: mw.msg( 'rollback-confirmation-yes' ),
				no: mw.msg( 'rollback-confirmation-no' )
			}
		} );
	} );
} );
