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
	var minTabIndex = null;
	$(this).find( '[tabindex]' ).each( function( i ) {
		var tabIndex = parseInt( $(this).attr( 'tabindex' ), 10 );
		if ( i === 0 ) {
			minTabIndex = tabIndex;
		// In IE6/IE7 the above jQuery selector returns all elements,
		// becuase it has a default value for tabIndex in IE6/IE7 of 0
		// (rather than null/undefined). Therefore check "> 0" as well
		} else if ( tabIndex > 0 && tabIndex < minTabIndex ) {
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
	var maxTabIndex = null;
	$(this).find( '[tabindex]' ).each( function( i ) {
		var tabIndex = parseInt( $(this).attr( 'tabindex' ), 10 );
		if ( i === 0 ) {
			maxTabIndex = tabIndex;
		} else if ( tabIndex > maxTabIndex ) {
			maxTabIndex = tabIndex;
		}
	} );
	return maxTabIndex;
};
} )( jQuery );
