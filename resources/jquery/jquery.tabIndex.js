/**
 * jQuery tabIndex
 */
( function( $ ) {
/**
 * Finds the lowerst tabindex in use within a selection
 * 
 * @return number Lowest tabindex on the page
 */
$.fn.firstTabIndex = function() {
	var minTabIndex = 0;
	$(this).find( '[tabindex]' ).each( function() {
		var tabIndex = parseInt( $(this).attr( 'tabindex' ), 10 );
		if ( tabIndex > minTabIndex ) {
			minTabIndex = tabIndex;
		}
	} );
	return minTabIndex;
};

/**
 * Finds the highest tabindex in use within a selection
 * 
 * @return number Highest tabindex on the page
 */
$.fn.lastTabIndex = function() {
	var maxTabIndex = 0;
	$(this).find( '[tabindex]' ).each( function() {
		var tabIndex = parseInt( $(this).attr( 'tabindex' ), 10 );
		if ( tabIndex > maxTabIndex ) {
			maxTabIndex = tabIndex;
		}
	} );
	return maxTabIndex;
};
} )( jQuery );