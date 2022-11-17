/*!
 * JavaScript for Special:Preferences: Tab navigation.
 */
( function () {
	// this function takes all the checkboxes in the Preferences of mobile & displays them as toggle switches
	// naming the function makes it easier to find in performance profiler tools.
	// local runtime: 12ms (+/- 2ms) total
	function insertToggles() {
		var checkboxes = document.querySelectorAll( 'span.oo-ui-checkboxInputWidget' );
		// Iterate through DOM elements instead of JQuery collection
		Array.prototype.forEach.call( checkboxes, function ( checkboxWidget ) {
			// Use DOM elements when OOUI functionality isn't required
			var checkboxInput = checkboxWidget.querySelector( 'input' );
			// It's fine to use OOUI to implement UI elements
			var toggleSwitchWidget = new OO.ui.ToggleSwitchWidget( {
				value: checkboxInput.checked,
				disabled: checkboxInput.disabled
			} );
			// No more event or state handling is required
			// since we only create one OOUI Widget per checkbox
			toggleSwitchWidget.on( 'change', function ( value ) {
				toggleSwitchWidget.setValue( value );
				checkboxInput.checked = value;
			} );

			// Use native JS methods to insert elements into the DOM
			// OO.ui.Widget.$element returns a JQuery object
			// That object is iterable and wraps a single DOM element, so
			// OO.ui.Widget.$element[ 0 ] is the DOM element rendition of this OOUI widget
			checkboxWidget.insertAdjacentElement( 'afterend', toggleSwitchWidget.$element[ 0 ] );
			// Use native JS methods to manage visibility of DOM elements
			checkboxWidget.classList.add( 'hidden' );
			// @TODO: T323050 verify that this doesn't break any exising checkbox handling
		} );
	}
	$( function () {
		insertToggles();
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
