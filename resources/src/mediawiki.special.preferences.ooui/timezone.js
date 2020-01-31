/*!
 * JavaScript for Special:Preferences: Timezone field enhancements.
 */
( function () {
	mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
		var timezoneWidget, $localtimeHolder, servertime,
			$target = $root.find( '#wpTimeCorrection' );

		if (
			// This preference could theoretically be disabled ($wgHiddenPrefs)
			!$target.length ||
			$target.closest( '.mw-htmlform-autoinfuse-lazy' ).length
		) {
			return;
		}

		// Timezone functions.
		// Guesses Timezone from browser and updates fields onchange.

		// This is identical to OO.ui.infuse( ... ), but it makes the class name of the result known.
		timezoneWidget = mw.widgets.SelectWithInputWidget.static.infuse( $target );

		$localtimeHolder = $( '#wpLocalTime' );
		servertime = parseInt( $( 'input[name="wpServerTime"]' ).val(), 10 );

		function minutesToHours( min ) {
			var tzHour = Math.floor( Math.abs( min ) / 60 ),
				tzMin = Math.abs( min ) % 60,
				tzString = ( ( min >= 0 ) ? '' : '-' ) + ( ( tzHour < 10 ) ? '0' : '' ) + tzHour +
					':' + ( ( tzMin < 10 ) ? '0' : '' ) + tzMin;
			return tzString;
		}

		function hoursToMinutes( hour ) {
			var minutes,
				arr = hour.split( ':' );

			arr[ 0 ] = parseInt( arr[ 0 ], 10 );

			if ( arr.length === 1 ) {
				// Specification is of the form [-]XX
				minutes = arr[ 0 ] * 60;
			} else {
				// Specification is of the form [-]XX:XX
				minutes = Math.abs( arr[ 0 ] ) * 60 + parseInt( arr[ 1 ], 10 );
				if ( arr[ 0 ] < 0 ) {
					minutes *= -1;
				}
			}
			// Gracefully handle non-numbers.
			if ( isNaN( minutes ) ) {
				return 0;
			} else {
				return minutes;
			}
		}

		function updateTimezoneSelection() {
			var timeZone, minuteDiff, localTime, newValue,
				type = timezoneWidget.dropdowninput.getValue();

			if ( type === 'other' ) {
				// User specified time zone manually in <input>
				// Grab data from the textbox, parse it.
				minuteDiff = hoursToMinutes( timezoneWidget.textinput.getValue() );
			} else {
				// Time zone not manually specified by user
				if ( type === 'guess' ) {
					// If available, get the named time zone from the browser.
					// (We also support older browsers where this API is not available.)
					try {
						// This may return undefined
						timeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
					} catch ( err ) {
						timeZone = null;
					}

					// Get the time offset
					minuteDiff = -( new Date().getTimezoneOffset() );

					if ( timeZone ) {
						// Try to save both time zone and offset
						newValue = 'ZoneInfo|' + minuteDiff + '|' + timeZone;
						timezoneWidget.dropdowninput.setValue( newValue );
					}
					if ( !timeZone || timezoneWidget.dropdowninput.getValue() !== newValue ) {
						// No time zone, or it's unknown to MediaWiki. Save only offset
						timezoneWidget.dropdowninput.setValue( 'other' );
						timezoneWidget.textinput.setValue( minutesToHours( minuteDiff ) );
					}

				} else {
					// Grab data from the dropdown value
					minuteDiff = parseInt( type.split( '|' )[ 1 ], 10 ) || 0;
				}
			}

			// Determine local time from server time and minutes difference, for display.
			localTime = servertime + minuteDiff;

			// Bring time within the [0,1440) range.
			localTime = ( ( localTime % 1440 ) + 1440 ) % 1440;

			$localtimeHolder.text( mw.language.convertNumber( minutesToHours( localTime ) ) );
		}

		timezoneWidget.dropdowninput.on( 'change', updateTimezoneSelection );
		timezoneWidget.textinput.on( 'change', updateTimezoneSelection );
		updateTimezoneSelection();

	} );
}() );
