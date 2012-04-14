/*
 * JavaScript for Special:Preferences
 */
jQuery( document ).ready( function( $ ) {
$( '#prefsubmit' ).attr( 'id', 'prefcontrol' );
var $preftoc = $('<ul id="preftoc"></ul>');
var $preferences = $( '#preferences' )
	.addClass( 'jsprefs' )
	.before( $preftoc );

var $fieldsets = $preferences.children( 'fieldset' )
	.hide()
	.addClass( 'prefsection' );

var $legends = $fieldsets.children( 'legend' )
	.addClass( 'mainLegend' );

// 'name' needs the name of a tab without a prefix (i.e. mw-prefsection).
// If 'mode' isn't 'noHash', a hash will be set according to the current open section.
// It uses document.getElementById for security reasons (JavaScript injections).
function switchPrefTab ( name,  mode ) {
	// Handle hash manually to prevent jumping
	// Therefore save and restore scrollTop to prevent jumping
	var scrollTop = $( window ).scrollTop();
	if ( mode !== 'noHash' ) {
		window.location.hash = '#mw-prefsection-' + name;
	}
	$( window ).scrollTop( scrollTop );

	$preftoc.find( 'li' ).removeClass( 'selected' );
	$( document.getElementById( 'preftab-' + name ).parentNode ).addClass( 'selected' );
	$( '#preferences > fieldset' ).hide();
	$( document.getElementById( 'mw-prefsection-' + name ) ).show();
}

// Populate the prefToc
$legends.each( function( i, legend ) {
	var $legend = $(legend);
	if ( i === 0 ) {
		$legend.parent().show();
	}
	var ident = $legend.parent().attr( 'id' );

	var $li = $( '<li/>', {
		'class' : ( i === 0 ) ? 'selected' : null
	});
	var $a = $( '<a/>', {
		text : $legend.text(),
		id : ident.replace( 'mw-prefsection', 'preftab' ),
		href : '#' + ident
	}).click( function( e ) {
		switchPrefTab( $( this ).attr( 'href' ).replace( '#mw-prefsection-', '' ) );
		e.preventDefault();
	});
	$li.append( $a );
	$preftoc.append( $li );
} );

// If we've reloaded the page or followed an open-in-new-window,
// make the selected tab visible.
var hash = window.location.hash;
if ( hash.match( /^#mw-prefsection-[\w-]+/ ) ) {
	switchPrefTab( hash.replace( '#mw-prefsection-' , '' ) );
}

// Not all browsers support this event
if ( 'onhashchange' in window ) {
	window.addEventListener( 'hashchange' , function () {
		var hash = window.location.hash;
		if ( hash.match( /^#mw-prefsection-[\w-]+/ ) ) {
			switchPrefTab( hash.replace( '#mw-prefsection-', '' ) );
		} else {
			switchPrefTab( 'personal', 'noHash' );
		}
	});
}

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

	var minutes;
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
	} else if ( type == 'other' ) {
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
} );
