/*
 * Vector-specific scripts
 */
jQuery( function( $ ) {

	// For accessibility, show the menu whe 
	// the hidden link in the menu is focused (bug 24298)
	$( 'div.vectorMenu' ).each( function() {
		var self = this;
		var focused = false;
		$( 'h5:first a:first', this )
			.click( function( e ) {
				e.preventDefault();
			} )
			// Blur the link if it was focused before the click
			.mousedown( function( e ) {
				focused = $( this ).is( ':focus' );
			} )
			.mouseup( function( e ) {
				if ( focused ) {
					$( this ).blur();
				}
				e.preventDefault();
			} )
			// When the hidden link has focus, show the menu
			// and set a class that will change the arrow icon
			.focus( function() {
				$( '.menu:first', self ).addClass( 'menuForceShow' );
				$( self ).addClass( 'vectorMenuFocus' );
			} )
			.blur( function() {
				$( '.menu:first', self ).removeClass( 'menuForceShow' );
				$( self ).removeClass( 'vectorMenuFocus' );
			} );
	} );
});
