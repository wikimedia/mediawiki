( function( $ ) {

/**
 * Finds the highest tabindex in use.
 * 
 * @return Integer of highest tabindex on the page
 */
$.fn.maxTabIndex( function() {
	var maxTabIndex = 0;
	$(this).find( '[tabindex]' ).each( function() {
		var tabIndex = parseInt( $(this).attr( 'tabindex' ) );
		if ( tabIndex > maxTabIndex ) {
			maxTabIndex = tabIndex;
		}
	} );
	return maxTabIndex;
} );

} )();