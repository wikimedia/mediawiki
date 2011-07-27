/*
 * JavaScript to enable right click edit functionality
 */
jQuery( function( $ ) {
	// Select all h1-h6 elements that contain editsection links
	$( '.mw-h1:has(.editsection a), ' +
		'.mw-h2:has(.editsection a), ' +
		'.mw-h3:has(.editsection a), ' +
		'.mw-h4:has(.editsection a), ' +
		'.mw-h5:has(.editsection a), ' +
		'.mw-h6:has(.editsection a)'
	).live( 'contextmenu', function( e ) {
		// Get href of the [edit] link
		var href = $(this).find( '.editsection a' ).attr( 'href' );
		// Check if target is the anchor link itself. If so, don't suppress the context menu; this
		// way the reader can still do things like copy URL, open in new tab etc.
		var $target = $( e.target );
		if ( !$target.is( 'a' ) && !$target.parent().is( '.editsection' ) ){
			window.location = href;
			e.preventDefault();
			return false;
		}
	} );
} );
