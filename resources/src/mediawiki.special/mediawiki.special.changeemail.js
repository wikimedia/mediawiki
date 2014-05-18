/*!
 * JavaScript for Special:ChangeEmail
 */
( function ( mw, $ ) {
	/**
	 * Given an email validity status (true, false, null) update the label CSS class
	 * @ignore
	 */
	function updateMailValidityLabel( mail ) {
		var isValid = mw.util.validateEmail( mail ),
			$label = $( '#mw-emailaddress-validity' );

		// Set up the validity notice if it doesn't already exist
		if ( $label.length === 0 ) {
			$label = $( '<label for="wpNewEmail" id="mw-emailaddress-validity"></label>' )
				.insertAfter( '#wpNewEmail' );
		}

		// We allow empty address
		if ( isValid === null ) {
			$label.text( '' ).removeClass( 'valid invalid' );

		// Valid
		} else if ( isValid ) {
			$label.text( mw.msg( 'email-address-validity-valid' ) ).addClass( 'valid' ).removeClass( 'invalid' );

		// Not valid
		} else {
			$label.text( mw.msg( 'email-address-validity-invalid' ) ).addClass( 'invalid' ).removeClass( 'valid' );
		}
	}

	$( function () {
		$( '#wpNewEmail' )
			// Lame tip to let user know if its email is valid. See bug 22449.
			// Only bind once for 'blur' so that the user can fill it in without errors;
			// after that, look at every keypress for immediate feedback.
			.one( 'blur', function () {
				var $this = $( this );
				updateMailValidityLabel( $this.val() );
				$this.keyup( function () {
					updateMailValidityLabel( $this.val() );
				} );
			} )
			// Supress built-in validation notice and just call updateMailValidityLabel(),
			// to avoid double notice. See bug 40909.
			.on( 'invalid', function ( e ) {
				e.preventDefault();
				updateMailValidityLabel( $( this ).val() );
			} );
	} );
}( mediaWiki, jQuery ) );
