/*
 * Javascript for module editWarning
 */
( function ( mw, $ ) {
	'use strict';

	$( function () {
		var allowCloseWindow,
			$wpTextbox1 = $( '#wpTextbox1' ),
			$wpSummary = $( '#wpSummary' );

		// Check if EditWarning is enabled and if we need it
		if ( $wpTextbox1.length === 0 ) {
			return true;
		}
		// Get the original values of some form elements
		$wpTextbox1.add( $wpSummary ).each( function () {
			$( this ).data( 'origtext', $( this ).val() );
		} );

		allowCloseWindow = mw.confirmCloseWindow( {
			test: function () {
				return mw.config.get( 'wgAction' ) === 'submit' ||
					$wpTextbox1.data( 'origtext' ) !== $wpTextbox1.textSelection( 'getContents' ) ||
					$wpSummary.data( 'origtext' ) !== $wpSummary.textSelection( 'getContents' );
			},

			message: function () {
				return mw.msg( 'editwarning-warning' );
			},

			namespace: 'editwarning'
		} );

		// Add form submission handler
		$( '#editform' ).submit( function () {
			allowCloseWindow();
		} );
	} );

}( mediaWiki, jQuery ) );
