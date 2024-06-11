/*!
 * JavaScript for:
 * - rollback confirmation prompt
 * - firing the postEdit hook upon a successful rollback edit
 */
( function () {
	// If a rollback was successful, RollbackAction.php will export wgRollbackSuccess
	var wgRollbackSuccess = mw.config.get( 'wgRollbackSuccess' );
	if ( wgRollbackSuccess ) {
		mw.loader.using( 'mediawiki.action.view.postEdit', () => {
			// wgCurRevisionId is set to the revision of the rollback
			// in OutputPage::getJSVars. wgRevisionId is 0, since we're looking at
			// the success page for the rollback and not the content of the page.
			// It might make sense to display a success message in a toast, like other
			// implementations of the postEdit hook, but for now we just want to
			// enable code that relies on postEdit firing to know that an edit
			// was made.
			mw.hook( 'postEdit' ).fire();
		} );
	}

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
