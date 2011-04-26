/*
 * Vector-specific scripts
 */
jQuery( function( $ ) {
	var $pCactions = $( '#p-cactions' );
	// For accessibility, show the menu when the hidden link in the menu is clicked
	$pCactions.find( 'h5 a' ).click( function() {
		$pCactions.find( '.menu' ).toggleClass( 'menuForceShow' );
	});

	// When the hidden link has focus, also set a class that will change the arrow icon
	$pCactions.find( 'h5 a' ).focus( function () {
		$pCactions.addClass( 'vectorMenuFocus' );
	});

	$pCactions.find( 'h5 a' ).blur( function () {
		$pCactions.removeClass( 'vectorMenuFocus' );
	});
});
