/*!
 * JavaScript for Special:Preferences: Email preferences better UX
 */
( function () {
	$( function () {
		var allowEmail, $allowEmail, allowEmailFromNewUsers, $allowEmailFromNewUsers;

		$allowEmail = $( '#wpAllowEmail' );
		$allowEmailFromNewUsers = $( '#wpAllowEmailFromNewUsers' );

		// This preference could theoretically be disabled ($wgHiddenPrefs)
		if ( !$allowEmail.length || !$allowEmailFromNewUsers.length ) {
			return;
		}

		allowEmail = OO.ui.infuse( $allowEmail );
		allowEmailFromNewUsers = OO.ui.infuse( $allowEmailFromNewUsers );

		function toggleDisabled() {
			allowEmailFromNewUsers.setDisabled( !allowEmail.isSelected() );
		}

		allowEmail.on( 'change', toggleDisabled );
		toggleDisabled();
	} );
}() );
