( function ( mw, $ ) {
	$( '#wpPassword2' ).on( 'change paste keyup', function () {
		var result, $meter,
			$password = $( this ),
			password = $password.val();

		if ( password.length > 0 ) {
			$meter = $( '#wpPasswordMeter' );
			if ( !$meter.length ) {
				$meter = $( '<div>' ).attr( 'id', 'wpPasswordMeter' );
				$password.after( $meter );
			}

			userInputs = [
				$( '#wpName2' ).val(),
				$( '#wpEmail' ).val(),
				$( '#wpRealName' ).val()
			];
			result = zxcvbn( password, userInputs );

			$meter.html(
				'Password: ' + password + '<br>' +
				'Score: ' + result.score + '<br>' +
				result.feedback.warning + '<br>' +
				result.feedback.suggestions
			);
		} else {
			$( '#wpPasswordMeter' ).remove();
		}
	} );
} )( mediaWiki, jQuery );

