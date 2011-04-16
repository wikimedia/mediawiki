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
										$(this).parent().addClass( 'selected' );
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



/**
* Timezone functions.
* Guesses Timezone from browser and updates fields onchange
*/

var $tzSelect = $( '#mw-input-wptimecorrection' );
var $tzTextbox = $( '#mw-input-wptimecorrection-other' );

var $localtimeHolder = $( '#wpLocalTime' );
var servertime = parseInt( $( 'input[name=wpServerTime]' ).val(), 10 );
var minuteDiff = 0;

var minutesToHours = function( min ) {
	var tzHour = Math.floor( Math.abs( min ) / 60 );
	var tzMin = Math.abs( min ) % 60;
	var tzString = ( ( min >= 0 ) ? '' : '-' ) + ( ( tzHour < 10 ) ? '0' : '' ) + tzHour +
		':' + ( ( tzMin < 10 ) ? '0' : '' ) + tzMin;
	return tzString;
};

var hoursToMinutes = function( hour ) {
	var arr = hour.split( ':' );
	arr[0] = parseInt( arr[0], 10 );
	
	if ( arr.length == 1 ) {
		// Specification is of the form [-]XX
		minutes = arr[0] * 60;
	} else {
		// Specification is of the form [-]XX:XX
		minutes = Math.abs( arr[0] ) * 60 + parseInt( arr[1], 10 );
		if ( arr[0] < 0 ) {
			minutes *= -1;
		}
	}
	// Gracefully handle non-numbers.
	if ( isNaN( minutes ) ) {
		return 0;
	} else {
		return minutes;
	}
};

var updateTimezoneSelection = function() {
	var type = $tzSelect.val();
	if ( type == 'guess' ) {
		// Get browser timezone & fill it in
		minuteDiff = -new Date().getTimezoneOffset();
		$tzTextbox.val( minutesToHours( minuteDiff ) );
		$tzSelect.val( 'other' );
		$tzTextbox.get( 0 ).disabled = false;
	} else if ( type == 'other'  ) {
		// Grab data from the textbox, parse it.
		minuteDiff = hoursToMinutes( $tzTextbox.val() );
	} else {
		// Grab data from the $tzSelect value
		minuteDiff = parseInt( type.split( '|' )[1], 10 ) || 0;
		$tzTextbox.val( minutesToHours( minuteDiff ) );
	}

	// Determine local time from server time and minutes difference, for display.
	var localTime = servertime + minuteDiff;

	// Bring time within the [0,1440) range.
	while ( localTime < 0 ) {
		localTime += 1440;
	}
	while ( localTime >= 1440 ) {
		localTime -= 1440;
	}
	$localtimeHolder.text( minutesToHours( localTime ) );
};

if ( $tzSelect.length && $tzTextbox.length ) {
	$tzSelect.change( function() { updateTimezoneSelection(); } );
	$tzTextbox.blur( function() { updateTimezoneSelection(); } );
	updateTimezoneSelection();
}
} )( jQuery, mediaWiki );