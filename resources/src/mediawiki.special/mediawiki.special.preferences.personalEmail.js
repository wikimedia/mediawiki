/*!
 * JavaScript for Special:Preferences: Email preferences better UX
 */
( function ( $, OO ) {
	$( function () {
		var allowEmailWidget, allowEmailFromNewUsersWidget;

		// because user email can be disabled by config per wiki and might not be there
		try {
			allowEmailWidget = OO.ui.CheckboxInputWidget.static.infuse( 'wpAllowEmail' );
			allowEmailFromNewUsersWidget = OO.ui.CheckboxInputWidget.static.infuse( 'wpAllowEmailFromNewUsers' );
		} catch ( err ) {
			allowEmailWidget = null;
			allowEmailFromNewUsersWidget = null;
		}

		function toggleDisabled() {
			if ( allowEmailWidget.isSelected() ) {
				allowEmailFromNewUsersWidget.setDisabled( false );
			} else {
				allowEmailFromNewUsersWidget.setDisabled( true ).setSelected( false );
			}
		}

		if ( allowEmailWidget ) {
			allowEmailWidget.on( 'change', toggleDisabled );
			toggleDisabled();
		}
	} );
}( jQuery, OO ) );
