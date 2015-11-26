( function ( $ ) {
	$( function () {
		var $label, labelText;

		function syncText() {
			var value = $( this ).val()
				.replace( /[\[\]\{\}|#<>%+? ]/g, '_' )
				.replace( /&/, '&amp;' )
				.replace( /__+/g, '_' )
				.replace( /^_+/, '' )
				.replace( /_+$/, '' );
			value = value.charAt( 0 ).toUpperCase() + value.slice( 1 );
			$label.text( labelText.replace( '$1', value ) );
		}

		// Set up the help system
		$( '.config-help-field-data' )
			.hide()
			.closest( '.config-help-field-container' )
				.find( '.config-help-field-hint' )
					.show()
					.click( function () {
						$( this )
							.closest( '.config-help-field-container' )
								.find( '.config-help-field-data' )
									.slideToggle( 'fast' );
					} );

		// Show/hide code for DB-specific options
		// FIXME: Do we want slow, fast, or even non-animated (instantaneous) showing/hiding here?
		$( '.dbRadio' ).each( function () {
			$( document.getElementById( $( this ).attr( 'rel' ) ) ).hide();
		} );
		$( document.getElementById( $( '.dbRadio:checked' ).attr( 'rel' ) ) ).show();
		$( '.dbRadio' ).click( function () {
			var $checked = $( '.dbRadio:checked' ),
				$wrapper = $( document.getElementById( $checked.attr( 'rel' ) ) );
			if ( $wrapper.is( ':hidden' ) ) {
				$( '.dbWrapper' ).hide( 'slow' );
				$wrapper.show( 'slow' );
			}
		} );

		// Scroll to the bottom of upgrade log
		$( '#config-live-log' ).children( 'textarea' ).each( function () {
			this.scrollTop = this.scrollHeight;
		} );

		// Show/hide Creative Commons thingy
		$( '.licenseRadio' ).click( function () {
			var $wrapper = $( '#config-cc-wrapper' );
			if ( $( '#config__LicenseCode_cc-choose' ).is( ':checked' ) ) {
				$wrapper.show( 'slow' );
			} else {
				$wrapper.hide( 'slow' );
			}
		} );

		// Show/hide random stuff (email, upload)
		$( '.showHideRadio' ).click( function () {
			var $wrapper = $( '#' + $( this ).attr( 'rel' ) );
			if ( $( this ).is( ':checked' ) ) {
				$wrapper.show( 'slow' );
			} else {
				$wrapper.hide( 'slow' );
			}
		} );
		$( '.hideShowRadio' ).click( function () {
			var $wrapper = $( '#' + $( this ).attr( 'rel' ) );
			if ( $( this ).is( ':checked' ) ) {
				$wrapper.hide( 'slow' );
			} else {
				$wrapper.show( 'slow' );
			}
		} );

		// Hide "other" textboxes by default
		// Should not be done in CSS for javascript disabled compatibility
		$( '.enabledByOther' ).closest( '.config-block' ).hide();

		// Enable/disable "other" textboxes
		$( '.enableForOther' ).click( function () {
			var $textbox = $( document.getElementById( $( this ).attr( 'rel' ) ) );
			// FIXME: Ugh, this is ugly
			if ( $( this ).val() === 'other' ) {
				$textbox.removeProp( 'readonly' ).closest( '.config-block' ).slideDown( 'fast' );
			} else {
				$textbox.prop( 'readonly', true ).closest( '.config-block' ).slideUp( 'fast' );
			}
		} );

		// Synchronize radio button label for sitename with textbox
		$label = $( 'label[for=config__NamespaceType_site-name]' );
		labelText = $label.text();
		$label.text( labelText.replace( '$1', '' ) );
		$( '#config_wgSitename' ).on( 'keyup change', syncText ).each( syncText );

		// Show/Hide memcached servers when needed
		$( 'input[name$="config_wgMainCacheType"]' ).change( function () {
			var $memc = $( '#config-memcachewrapper' );
			if ( $( 'input[name$="config_wgMainCacheType"]:checked' ).val() === 'memcached' ) {
				$memc.show( 'slow' );
			} else {
				$memc.hide( 'slow' );
			}
		} );
	} );
}( jQuery ) );
