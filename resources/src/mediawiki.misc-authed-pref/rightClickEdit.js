/*!
 * Enable right-click-to-edit functionality.
 *
 * When the user right-clicks in a content heading, it will open the
 * edit section link.
 */
( function () {
	if ( Number( mw.user.options.get( 'editsectiononrightclick' ) ) !== 1 ) {
		// Support both 1 or "1" (T54542)
		return;
	}

	// Trigger this when a contextmenu click on the page targets a wikitext heading element.
	// This uses a delegate handler which 1) starts immediately instead of blocking
	// response on dom-ready, and 2) selects and binds once instead of N times.
	$( document ).on( 'contextmenu', '.mw-heading', function ( e ) {
		// Don't use ":has:(.mw-editsection a)" in the selector because it's slow.
		const $edit = $( this ).find( '.mw-editsection a' );
		if ( !$edit.length ) {
			return;
		}

		// Headings can contain rich text.
		// Make sure to not block contextmenu events on (other) anchor tags
		// inside the heading (e.g. to do things like copy URL, open in new tab, ..).
		// e.target can be the heading, but it can also be anything inside the heading.
		if ( !$( e.target ).closest( 'a' ).length ) {
			// Trigger native HTMLElement click instead of opening URL (T45052)
			e.preventDefault();
			$edit.get( 0 ).click();
		}
	} );
}() );
