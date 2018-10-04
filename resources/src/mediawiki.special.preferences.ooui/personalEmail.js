/*!
 * JavaScript for Special:Preferences: Email preferences better UX
 */
( function () {
	$( function () {
		var allowEmail, allowEmailFromNewUsers;

		allowEmail = $( '#wpAllowEmail' );
		allowEmailFromNewUsers = $( '#wpAllowEmailFromNewUsers' );

		function toggleDisabled() {
			if ( allowEmail.is( ':checked' ) && allowEmail.is( ':enabled' ) ) {
				allowEmailFromNewUsers.prop( 'disabled', false );
			} else {
				allowEmailFromNewUsers.prop( 'disabled', true );
			}
		}

		if ( allowEmail ) {
			allowEmail.on( 'change', toggleDisabled );
			toggleDisabled();
		}
	} );
}() );
