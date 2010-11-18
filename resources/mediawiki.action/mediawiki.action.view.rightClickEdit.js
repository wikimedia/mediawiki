/*
 * JavaScript to enable right click edit functionality
 */
$( function() {
	// Select all h1-h6 elements that contain editsection links
	$( 'h1:has(.editsection a), ' +
		'h2:has(.editsection a), ' +
		'h3:has(.editsection a), ' +
		'h4:has(.editsection a), ' +
		'h5:has(.editsection a), ' +
		'h6:has(.editsection a)'
	).live( 'contextmenu', function( e ) {
		// Get href of the [edit] link
		var href = $(this).find( '.editsection a' ).attr( 'href' );
		// Check if target is the anchor link itself. If so, don't suppress the context menu; this
		// way the reader can still do things like copy URL, open in new tab etc.
		$target = $( e.target );
		if ( !$target.is( 'a' ) && !$target.parent().is( '.editsection' ) ){
			window.location = href;
			e.preventDefault();
			return false;
		}
	} );
} );
