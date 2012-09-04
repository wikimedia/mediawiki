/*
 * JavaScript to enable right click edit functionality.
 * When the user right-clicks in a heading, it will open the
 * edit screen.
 */
jQuery( function ( $ ) {
	// Select all h1-h6 elements that contain editsection links
	// Don't use the ":has:(.editsection a)" selector because it performs very bad.
	// http://jsperf.com/jq-1-7-2-vs-jq-1-8-1-performance-of-mw-has/2
	$( document ).on( 'contextmenu', 'h1, h2, h3, h4, h5, h6', function ( e ) {
		var $edit, href;

		$edit = $( this ).find( '.editsection a' );
		if ( !$edit.length ) {
			return;
		}

		// Get href of the editsection link
		href = $edit.prop( 'href' );

		// Headings can contain rich text.
		// Make sure to not block contextmenu events on (other) anchor tags
		// inside the heading (e.g. to do things like copy URL, open in new tab, ..).
		// e.target can be the heading, but it can also be anything inside the heading.
		if ( href && e.target.nodeName.toLowerCase() !== 'a' ) {
			window.location = href;
			e.preventDefault();
		}
	} );
} );
