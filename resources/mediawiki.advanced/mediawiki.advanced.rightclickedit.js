/*
 * JavaScript to enable right click edit functionality
 */

// Select all h1-h6 elements that contain editsection links
$('h1, h2, h3, h4, h5, h6').filter( ':has(.editsection a)' ).bind( 'contextmenu', function( e ) {

	// Get href of the [edit] link
	var href = $(this).find( '.editsection a' ).attr( 'href' );

	// Check if target is the anchor link itself. If so, dont supress the contextmenu
	// So that the reader can still do things like copy url, open in new tab etc.
	$target = $( e.target );
	if( !$target.is( 'a' ) && !$target.parent().is( '.editsection' ) ){
		window.location = href;
		e.preventDefault();
	}

});