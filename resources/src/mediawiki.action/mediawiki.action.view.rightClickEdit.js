/*!
 * JavaScript to enable right click edit functionality.
 * When the user right-clicks in a heading, it will open the
 * edit screen.
 */
( function ( $ ) {
	// Trigger this when a contextmenu click on the page targets an h1-h6 element.
	// This uses a delegate handler which 1) starts immediately instead of blocking
	// response on dom-ready, and 2) selects and binds once instead of N times.
	$( document ).on( 'contextmenu', 'h1, h2, h3, h4, h5, h6', function ( e ) {
		// Don't use ":has:(.mw-editsection a)" in the selector because it's slow.
		var $edit = $( this ).find( '.mw-editsection a' );
		if ( !$edit.length ) {
			return;
		}

		// Headings can contain rich text.
		// Make sure to not block contextmenu events on (other) anchor tags
		// inside the heading (e.g. to do things like copy URL, open in new tab, ..).
		// e.target can be the heading, but it can also be anything inside the heading.
		if ( e.target.nodeName.toLowerCase() !== 'a' ) {
			// Trigger native HTMLElement click instead of opening URL (T45052)
			e.preventDefault();
			$edit.get( 0 ).click();
		}
	} );
}( jQuery ) );
