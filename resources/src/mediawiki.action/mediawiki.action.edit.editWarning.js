/*
 * Javascript for module editWarning
 */
( function () {
	'use strict';

	$( function () {
		var allowCloseWindow,
			$textBox = $( '#wpTextbox1' ),
			$summary = $( '#wpSummary' ),
			$both = $textBox.add( $summary );

		// Check if EditWarning is enabled and if we need it
		if ( !mw.user.options.get( 'useeditwarning' ) ) {
			return true;
		}

		// Save the original value of the text fields
		$both.each( function ( index, element ) {
			var $element = $( element );
			$element.data( 'origtext', $element.textSelection( 'getContents' ) );
		} );

		allowCloseWindow = mw.confirmCloseWindow( {
			test: function () {
				// We use .textSelection, because editors might not have updated the form yet.
				return mw.config.get( 'wgAction' ) === 'submit' ||
					$textBox.data( 'origtext' ) !== $textBox.textSelection( 'getContents' ) ||
					$summary.data( 'origtext' ) !== $summary.textSelection( 'getContents' );
			},

			message: mw.msg( 'editwarning-warning' ),
			namespace: 'editwarning'
		} );

		// Add form submission handler
		$( '#editform' ).on( 'submit', function () {
			allowCloseWindow.release();
		} );
	} );

}() );
