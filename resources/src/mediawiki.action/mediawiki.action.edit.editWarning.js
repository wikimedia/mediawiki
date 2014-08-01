/*
 * Javascript for module editWarning
 */
( function ( mw, $ ) {
	'use strict';

	$( function () {
		var allowCloseWindow,
			$editForm = $( '#editform' );

		// Check if EditWarning is enabled and if we need it
		if ( !mw.user.options.get( 'useeditwarning' ) ) {
			return true;
		}

		// Save the original value of all form elements
		$editForm.data( 'origtext', $editForm.serialize() );

		allowCloseWindow = mw.confirmCloseWindow( {
			test: function () {
				return mw.config.get( 'wgAction' ) === 'submit' ||
					$editForm.data( 'origtext' ) !== $editForm.serialize();
			},

			message: mw.msg( 'editwarning-warning' ),
			namespace: 'editwarning'
		} );

		// Add form submission handler
		$editForm.submit( function () {
			allowCloseWindow();
		} );
	} );

}( mediaWiki, jQuery ) );
