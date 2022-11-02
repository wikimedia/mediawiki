/*!
 * JavaScript for Special:Preferences: Tab navigation.
 */
( function () {
	$( function () {
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
