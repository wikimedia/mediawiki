/*
 * Vector-specific scripts
 */
$(document).ready( function() {	
	// For accessibility, show the menu when the hidden link in the menu is clicked
	$( '#p-cactions h5 a' ).click( function() {
		$( '#p-cactions .menu' ).toggleClass( 'menuForceShow' );
	});
	
	// When the hidden link has focus, also set a class that will change the arrow icon
	$( '#p-cactions h5 a' ).focus( function () {
		$( '#p-cactions' ).addClass( 'vectorMenuFocus' );
	});
	
	$( '#p-cactions h5 a' ).blur( function () {
		$( '#p-cactions' ).removeClass( 'vectorMenuFocus' );
	});
});
