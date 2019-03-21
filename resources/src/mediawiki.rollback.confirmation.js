/*!
 * JavaScript for rollback confirmation prompt
 */
( function () {

	var postRollback = function ( url ) {
		var $form = $( '<form>', {
			action: url,
			method: 'post'
		} );
		$form.appendTo( 'body' ).trigger( 'submit' );
	};

	$( '#mw-content-text' ).confirmable( {
		i18n: {
			confirm: mw.msg( 'rollback-confirmation-confirm', $( this ).data( 'rollback-count' ) ),
			yes: mw.msg( 'rollback-confirmation-yes' ),
			no: mw.msg( 'rollback-confirmation-no' )
		},
		delegate: '.mw-rollback-link a',
		handler: function ( e ) {
			e.preventDefault();
			postRollback( $( this ).attr( 'href' ) );
		}
	} );

}() );
