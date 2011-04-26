/*
 * Vector-specific scripts
 */
jQuery( function( $ ) {
	var $pCactions = $( '#p-cactions' );
	$pCactions.find( 'h5 a' )
		// For accessibility, show the menu when the hidden link in the menu is clicked (bug 24298)
		.click( function( e ) {
			$pCactions.find( '.menu' ).toggleClass( 'menuForceShow' );
			e.preventDefault();
		})
		// When the hidden link has focus, also set a class that will change the arrow icon
		.focus( function() {
			$pCactions.addClass( 'vectorMenuFocus' );
		})
		.blur( function() {
			$pCactions.removeClass( 'vectorMenuFocus' );
		});
});
