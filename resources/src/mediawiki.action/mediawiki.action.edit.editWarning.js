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

		// This registers an event with the name "beforeunload.editwarning", which allows others to
		// turn the confirmation off with `$( window ).off( 'beforeunload.editwarning' );`.
		allowCloseWindow = mw.confirmCloseWindow( {
			test: function () {
				// When the action is submit we're solving a conflict. Everything is a pending change there.
				return mw.config.get( 'wgAction' ) === 'submit' ||
					// We use .textSelection, because editors might not have updated the form yet.
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
