/*!
 * JavaScript for Special:Preferences
 */
( function ( mw, $ ) {
	$( function () {
		var $preftoc, $preferences, $fieldsets,
			labelFunc,
			$tzSelect, $tzTextbox, $localtimeHolder, servertime,
			allowCloseWindow, notif;

		labelFunc = function () {
			return this.id.replace( /^mw-prefsection/g, 'preftab' );
		};

		$( '#prefsubmit' ).attr( 'id', 'prefcontrol' );
		$preftoc = $( '#preftoc' );
		$preferences = $( '#preferences' );

		$fieldsets = $preferences.children( 'fieldset' )
			.attr( {
				role: 'tabpanel',
				'aria-labelledby': labelFunc
			} );
		$fieldsets.not( '#mw-prefsection-personal' )
				.hide()
				.attr( 'aria-hidden', 'true' );

		// T115692: The following is kept for backwards compatibility with older skins
		$preferences.addClass( 'jsprefs' );
		$fieldsets.addClass( 'prefsection' );
		$fieldsets.children( 'legend' ).addClass( 'mainLegend' );

		// Make sure the accessibility tip is selectable so that screen reader users take notice,
		// but hide it per default to reduce interface clutter. Also make sure it becomes visible
		// when selected. Similar to jquery.mw-jump
		$( '<div>' ).addClass( 'mw-navigation-hint' )
			.text( mw.msg( 'prefs-tabs-navigation-hint' ) )
			.attr( 'tabIndex', 0 )
			.on( 'focus blur', function ( e ) {
				if ( e.type === 'blur' || e.type === 'focusout' ) {
					$( this ).css( 'height', '0' );
				} else {
					$( this ).css( 'height', 'auto' );
				}
			} ).insertBefore( $preftoc );

		/**
		 * It uses document.getElementById for security reasons (HTML injections in $()).
		 *
		 * @ignore
		 * @param {string} name the name of a tab without the prefix ("mw-prefsection-")
		 * @param {string} [mode] A hash will be set according to the current
		 *  open section. Set mode 'noHash' to surpress this.
		 */
		function switchPrefTab( name, mode ) {
			var $tab, scrollTop;
			// Handle hash manually to prevent jumping,
			// therefore save and restore scrollTop to prevent jumping.
			scrollTop = $( window ).scrollTop();
			if ( mode !== 'noHash' ) {
				location.hash = '#mw-prefsection-' + name;
			}
			$( window ).scrollTop( scrollTop );

			$preftoc.find( 'li' ).removeClass( 'selected' )
				.find( 'a' ).attr( {
					tabIndex: -1,
					'aria-selected': 'false'
				} );

			$tab = $( document.getElementById( 'preftab-' + name ) );
			if ( $tab.length ) {
				$tab.attr( {
					tabIndex: 0,
					'aria-selected': 'true'
				} )
				.focus()
					.parent().addClass( 'selected' );

				$preferences.children( 'fieldset' ).hide().attr( 'aria-hidden', 'true' );
				$( document.getElementById( 'mw-prefsection-' + name ) ).show().attr( 'aria-hidden', 'false' );
			}
		}

		// Check for messageboxes (.successbox, .warningbox, .errorbox) to replace with notifications
		if ( $( '.mw-preferences-messagebox' ).length ) {
			// If there is a #mw-preferences-success box and javascript is enabled, use a slick notification instead!
			if ( $( '#mw-preferences-success' ).length ) {
				notif = mw.notification.notify( mw.message( 'savedprefs' ), { autoHide: false } );
				// 'change' event not reliable!
				$( '#preftoc, .prefsection' ).one( 'change keydown mousedown', function () {
					if ( notif ) {
						notif.close();
						notif = null;
					}
				} );
			}
		}

		// Enable keyboard users to use left and right keys to switch tabs
		$preftoc.on( 'keydown', function ( event ) {
			var keyLeft = 37,
				keyRight = 39,
				$el;

			if ( event.keyCode === keyLeft ) {
				$el = $( '#preftoc li.selected' ).prev().find( 'a' );
			} else if ( event.keyCode === keyRight ) {
				$el = $( '#preftoc li.selected' ).next().find( 'a' );
			} else {
				return;
			}
			if ( $el.length > 0 ) {
				switchPrefTab( $el.attr( 'href' ).replace( '#mw-prefsection-', '' ) );
			}
		} );

		// Jump to correct section as indicated by the hash.
		// This function is called onload and onhashchange.
		function detectHash() {
			var hash = location.hash,
				matchedElement, parentSection;
			if ( hash.match( /^#mw-prefsection-[\w\-]+/ ) ) {
				switchPrefTab( hash.replace( '#mw-prefsection-', '' ) );
			} else if ( hash.match( /^#mw-[\w\-]+/ ) ) {
				matchedElement = document.getElementById( hash.slice( 1 ) );
				parentSection = $( matchedElement ).closest( '.prefsection' );
				if ( parentSection.length ) {
					// Switch to proper tab and scroll to selected item.
					switchPrefTab( parentSection.attr( 'id' ).replace( 'mw-prefsection-', '' ), 'noHash' );
					matchedElement.scrollIntoView();
				}
			}
		}

		// In browsers that support the onhashchange event we will not bind click
		// handlers and instead let the browser do the default behavior (clicking the
		// <a href="#.."> will naturally set the hash, handled by onhashchange.
		// But other things that change the hash will also be caught (e.g. using
		// the Back and Forward browser navigation).
		// Note the special check for IE "compatibility" mode.
		if ( 'onhashchange' in window &&
			( document.documentMode === undefined || document.documentMode >= 8 )
		) {
			$( window ).on( 'hashchange', function () {
				var hash = location.hash;
				if ( hash.match( /^#mw-[\w\-]+/ ) ) {
					detectHash();
				} else if ( hash === '' ) {
					switchPrefTab( 'personal', 'noHash' );
				}
			} )
			// Run the function immediately to select the proper tab on startup.
			.trigger( 'hashchange' );
		// In older browsers we'll bind a click handler as fallback.
		// We must not have onhashchange *and* the click handlers, otherwise
		// the click handler calls switchPrefTab() which sets the hash value,
		// which triggers onhashchange and calls switchPrefTab() again.
		} else {
			$preftoc.on( 'click', 'li a', function ( e ) {
				switchPrefTab( $( this ).attr( 'href' ).replace( '#mw-prefsection-', '' ) );
				e.preventDefault();
			} );
			// If we've reloaded the page or followed an open-in-new-window,
			// make the selected tab visible.
			detectHash();
		}

		// Timezone functions.
		// Guesses Timezone from browser and updates fields onchange.

		$tzSelect = $( '#mw-input-wptimecorrection' );
		$tzTextbox = $( '#mw-input-wptimecorrection-other' );
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
			var minuteDiff, localTime,
				type = $tzSelect.val();

			if ( type === 'other' ) {
				// User specified time zone manually in <input>
				// Grab data from the textbox, parse it.
				minuteDiff = hoursToMinutes( $tzTextbox.val() );
			} else {
				// Time zone not manually specified by user
				if ( type === 'guess' ) {
					// Get browser timezone & fill it in
					minuteDiff = -( new Date().getTimezoneOffset() );
					$tzTextbox.val( minutesToHours( minuteDiff ) );
					$tzSelect.val( 'other' );
					$tzTextbox.prop( 'disabled', false );
				} else {
					// Grab data from the $tzSelect value
					minuteDiff = parseInt( type.split( '|' )[ 1 ], 10 ) || 0;
					$tzTextbox.val( minutesToHours( minuteDiff ) );
				}

				// Set defaultValue prop on the generated box so we don't trigger the
				// unsaved preferences check
				$tzTextbox.prop( 'defaultValue', $tzTextbox.val() );
			}

			// Determine local time from server time and minutes difference, for display.
			localTime = servertime + minuteDiff;

			// Bring time within the [0,1440) range.
			localTime = ( ( localTime % 1440 ) + 1440 ) % 1440;

			$localtimeHolder.text( mw.language.convertNumber( minutesToHours( localTime ) ) );
		}

		if ( $tzSelect.length && $tzTextbox.length ) {
			$tzSelect.change( updateTimezoneSelection );
			$tzTextbox.blur( updateTimezoneSelection );
			updateTimezoneSelection();
		}

		// Preserve the tab after saving the preferences
		// Not using cookies, because their deletion results are inconsistent.
		// Not using jStorage due to its enormous size (for this feature)
		if ( window.sessionStorage ) {
			if ( sessionStorage.getItem( 'mediawikiPreferencesTab' ) !== null ) {
				switchPrefTab( sessionStorage.getItem( 'mediawikiPreferencesTab' ), 'noHash' );
			}
			// Deleting the key, the tab states should be reset until we press Save
			sessionStorage.removeItem( 'mediawikiPreferencesTab' );

			$( '#mw-prefs-form' ).submit( function () {
				var storageData = $( $preftoc ).find( 'li.selected a' ).attr( 'id' ).replace( 'preftab-', '' );
				sessionStorage.setItem( 'mediawikiPreferencesTab', storageData );
			} );
		}

		// Check if all of the form values are unchanged
		function isPrefsChanged() {
			var inputs = $( '#mw-prefs-form :input[name]' ),
				input, $input, inputType,
				index, optIndex,
				opt;

			for ( index = 0; index < inputs.length; index++ ) {
				input = inputs[ index ];
				$input = $( input );

				// Different types of inputs have different methods for accessing defaults
				if ( $input.is( 'select' ) ) {
					// <select> has the property defaultSelected for each option
					for ( optIndex = 0; optIndex < input.options.length; optIndex++ ) {
						opt = input.options[ optIndex ];
						if ( opt.selected !== opt.defaultSelected ) {
							return true;
						}
					}
				} else if ( $input.is( 'input' ) ) { // <input> has defaultValue or defaultChecked
					inputType = input.type;
					if ( inputType === 'radio' || inputType === 'checkbox' ) {
						if ( input.checked !== input.defaultChecked ) {
							return true;
						}
					} else if ( input.value !== input.defaultValue ) {
						return true;
					}
				}
			}

			return false;
		}

		// Disable the button to save preferences unless preferences have changed
		// Check if preferences have been changed before JS has finished loading
		if ( !isPrefsChanged() ) {
			$( '#prefcontrol' ).prop( 'disabled', true );
			$( '#preferences > fieldset' ).one( 'change keydown mousedown', function () {
				$( '#prefcontrol' ).prop( 'disabled', false );
			} );
		}

		// Set up a message to notify users if they try to leave the page without
		// saving.
		allowCloseWindow = mw.confirmCloseWindow( {
			test: isPrefsChanged,
			message: mw.msg( 'prefswarning-warning', mw.msg( 'saveprefs' ) ),
			namespace: 'prefswarning'
		} );
		$( '#mw-prefs-form' ).submit( $.proxy( allowCloseWindow, 'release' ) );
		$( '#mw-prefs-restoreprefs' ).click( $.proxy( allowCloseWindow, 'release' ) );
	} );
}( mediaWiki, jQuery ) );
