/*
 * JavaScript for Special:Preferences
 */
( function( $, mw ) {

$( '#prefsubmit' ).attr( 'id', 'prefcontrol' );
$( '#preferences' )
	.addClass( 'jsprefs' )
	.before( $( '<ul id="preftoc"></ul>' ) )
	.children( 'fieldset' )
		.hide()
		.addClass( 'prefsection' )
		.children( 'legend' )
			.addClass( 'mainLegend' )
			.each( function( i, legend ) {
					$(legend).parent().attr( 'id', 'prefsection-' + i );
					if ( i === 0 ) {
						$(legend).parent().show();
					}
					$( '#preftoc' ).append(
						$( '<li></li>' )
							.addClass( i === 0 ? 'selected' : null )
							.append(
								$( '<a></a>')
									.text( $(legend).text() )
									.attr( 'id', 'preftab-' + i + '-tab' )
									.attr( 'href', '#preftab-' + i ) // Use #preftab-N instead of #prefsection-N to avoid jumping on click
									.click( function() {
										$(this).parent().parent().find( 'li' ).removeClass( 'selected' );
										$(this).parent().addClass( 'selected' )
										$( '#preferences > fieldset' ).hide();
										$( '#prefsection-' + i ).show();
									} )
							)
					);
				}
			);

// If we've reloaded the page or followed an open-in-new-window,
// make the selected tab visible.
// On document ready:
$( function() {
	var hash = window.location.hash;
	if( hash.match( /^#preftab-[\d]+$/ ) ) {
		var $tab = $( hash + '-tab' );
		$tab.click();
	}
} );

/**
 * Given an email validity status (true, false, null) update the label CSS class
 */
var updateMailValidityLabel = function( mail ) {
	var	isValid = mw.util.validateEmail( mail ),
		$label = $( '#mw-emailaddress-validity' );

	// We allow empty address
	if( isValid === null ) {
		$label.text( '' ).removeClass( 'valid invalid' );

	// Valid
	} else if ( isValid ) {
		$label.text( mw.msg( 'email-address-validity-valid' ) ).addClass( 'valid' ).removeClass( 'invalid' );

	// Not valid
	} else {
		$label.text( mw.msg( 'email-address-validity-invalid' ) ).addClass( 'invalid' ).removeClass( 'valid' );
	}
};

// Lame tip to let user know if its email is valid. See bug 22449
// Only bind once for 'blur' so that the user can fill it in without errors
// After that look at every keypress for direct feedback if it was invalid onblur
$( '#mw-input-wpemailaddress' ).one( 'blur', function() {
	if ( $( '#mw-emailaddress-validity' ).length === 0 ) {
		$(this).after( '<label for="mw-input-wpemailaddress" id="mw-emailaddress-validity"></label>' );
	}
	updateMailValidityLabel( $(this).val() );
	$(this).keyup( function() {
		updateMailValidityLabel( $(this).val() );
	} );
} );
} )( jQuery, mediaWiki );