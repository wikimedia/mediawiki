/*
 * Javascript for module editWarning
 */
( function ( mw, $ ) {
	'use strict';

	$( function () {
		var allowCloseWindow;

		// Check if EditWarning is enabled and if we need it
		if ( !mw.user.options.get( 'useeditwarning' ) ) {
			return true;
		}

		// Save the original value of the text fields
		$( '#wpTextbox1, #wpSummary' ).each( function ( index, element ) {
			var $element = $( element );
			$element.data( 'origtext', $element.textSelection( 'getContents' ) );
		} );

		allowCloseWindow = mw.confirmCloseWindow( {
			test: function () {
				// We use .textSelection, because editors might not have updated the form yet.
				return mw.config.get( 'wgAction' ) === 'submit' ||
					$('#wpTextbox1').data( 'origtext' ) !== $('#wpTextbox1').textSelection( 'getContents' ) ||
					$('#wpSummary').data( 'origtext' ) !== $('#wpSummary').textSelection( 'getContents' );
			},

			message: mw.msg( 'editwarning-warning' ),
			namespace: 'editwarning'
		} );

		// Add form submission handler
		$( '#editform' ).submit( function () {
			allowCloseWindow();
		} );
	} );

}( mediaWiki, jQuery ) );
