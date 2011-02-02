/**
 * jQuery tabIndex
 */
( function( $ ) {
/**
 * Finds the lowerst tabindex in use within a selection
 * 
 * @return Integer of lowest tabindex on the page
 */
jQuery.fn.firstTabIndex = function() {
	var minTabIndex = 0;
	jQuery(this).find( '[tabindex]' ).each( function() {
		var tabIndex = parseInt( jQuery(this).attr( 'tabindex' ) );
		if ( tabIndex > minTabIndex ) {
			minTabIndex = tabIndex;
		}
	} );
	return minTabIndex;
};
/**
 * Finds the highest tabindex in use within a selection
 * 
 * @return Integer of highest tabindex on the page
 */
jQuery.fn.lastTabIndex = function() {
	var maxTabIndex = 0;
	jQuery(this).find( '[tabindex]' ).each( function() {
		var tabIndex = parseInt( jQuery(this).attr( 'tabindex' ) );
		if ( tabIndex > maxTabIndex ) {
			maxTabIndex = tabIndex;
		}
	} );
	return maxTabIndex;
};
} )( jQuery );