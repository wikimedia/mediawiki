/*!
 * JavaScript for Special:Preferences: Tab navigation.
 */
( function () {
	$( function () {
		// this function takes all the checkboxes in the Preferences of mobile & displays them as toggle switches
		$( 'span.oo-ui-checkboxInputWidget' ).each( function () {
			var checkboxinputDisplayedAsToggleSwitch = OO.ui.infuse( $( this ) );
			var toggleSwitch = new OO.ui.ToggleSwitchWidget( {
				value: checkboxinputDisplayedAsToggleSwitch.isSelected(),
				disabled: checkboxinputDisplayedAsToggleSwitch.disabled
			} );
			var $checkboxBeingDisplayedAsToggleSwitch = checkboxinputDisplayedAsToggleSwitch.$element;
			toggleSwitch.$element.insertAfter( $checkboxBeingDisplayedAsToggleSwitch );
			$checkboxBeingDisplayedAsToggleSwitch.hide();

			// check to see if the toggle/ckbox is enabled (can be disabled due to requirements for display)
			// @TODO  Jsn.sherman
			// It occurred to me that we should probably check to see what happens if a checkbox already has
			// it's own custom onclick/onchange event handling. I think we'd end up paving right over it.
			// I thought to create that scenario locally to see if we should check for it and return early
			// (before replacing the checkbox), but I ran out of time.
			if ( toggleSwitch.disabled ) {
				return;
			}

			// listening on checkbox change is required to make the clicking work
			$checkboxBeingDisplayedAsToggleSwitch.on( 'change', function () {
				// disable checkbox
				$checkboxBeingDisplayedAsToggleSwitch.attr( 'disabled', true );
				var cbval = $checkboxBeingDisplayedAsToggleSwitch.getValue();
				toggleSwitch.setValue( cbval );
			} );

			toggleSwitch.on( 'change', function ( value ) {
				toggleSwitch.setValue( value );
				$checkboxBeingDisplayedAsToggleSwitch.find( 'input' ).prop(
					'checked', value );
			} );
		} );

		var $prefContent, prefContentId;
		var options = OO.ui.infuse( $( '.mw-mobile-preferences-container' ) );
		var $preferencesContainer = $( '#preferences' );
		var $prefOptionsContainer = $( '#mw-prefs-container' );
		function triggerPreferenceMenu( elementId ) {
			prefContentId = elementId + '-content';
			$prefContent = $( '#' + prefContentId );
			$prefContent.removeClass( 'mw-prefs-hidden' );
			$prefContent.attr( 'style', 'display:block;' );
			$prefOptionsContainer.addClass( 'mw-prefs-hidden' );
			$prefOptionsContainer.removeAttr( 'style' );
			$preferencesContainer.prepend( $prefContent );
			// Snippet based on https://stackoverflow.com/a/58944651/4612594
			// This prevents the page from scrolling down to where it was previously.
			if ( 'scrollRestoration' in history ) {
				history.scrollRestoration = 'manual';
			}
			window.scrollTo( 0, 0 );
		}
		function triggerBackToOptions( elementId ) {
			prefContentId = elementId + '-content';
			$prefContent = $( '#' + prefContentId );
			$prefOptionsContainer.removeClass( 'mw-prefs-hidden' );
			$prefOptionsContainer.attr( 'style', 'display:block;' );
			$prefContent.addClass( 'mw-prefs-hidden' );
			$prefContent.removeAttr( 'style' );
			$preferencesContainer.prepend( $prefOptionsContainer );
		}
		// Add a click event for each preference option
		options.items.forEach( function ( element ) {
			$( '#' + element.elementId ).on( 'click', function () {
				triggerPreferenceMenu( element.elementId );
			} );
			var backButtonId = '#' + element.elementId + '-back-button';
			$( backButtonId ).on( 'click', function () {
				triggerBackToOptions( element.elementId );
			} );
		} );
	} );
}() );
