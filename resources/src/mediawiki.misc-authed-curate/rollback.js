/*!
 * JavaScript for rollback confirmation prompt
 */
( function () {
	if ( Number( mw.user.options.get( 'showrollbackconfirmation' ) ) !== 1 ) {
		// Support both 1 or "1" (T54542)
		return;
	}

	function postRollback( url ) {
		$( '<form>' )
			.attr( {
				action: url,
				method: 'post'
			} )
			.appendTo( document.body )
			.trigger( 'submit' );
	}

	$( '#mw-content-text' ).confirmable( {
		i18n: {
			confirm: mw.msg( 'rollback-confirmation-confirm' ),
			yes: mw.msg( 'rollback-confirmation-yes' ),
			no: mw.msg( 'rollback-confirmation-no' )
		},
		delegate: '.mw-rollback-link a[data-mw="interface"]',
		handler: function ( e ) {
			e.preventDefault();
			postRollback( $( this ).attr( 'href' ) );
		}
	} );

}() );
