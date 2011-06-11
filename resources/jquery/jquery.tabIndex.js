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
	$(this).find( '[tabindex]' ).each( function() {
		var tabIndex = parseInt( $(this).attr( 'tabindex' ), 10 );
		// In IE6/IE7 the above jQuery selector returns all elements,
		// becuase it has a default value for tabIndex in IE6/IE7 of 0
		// (rather than null/undefined). Therefore check "> 0" as well.
		// Under IE7 under Windows NT 5.2 is also capable of returning NaN.
		if ( tabIndex > 0 && !isNaN( tabIndex ) ) {
			// Initial value
			if ( minTabIndex === null ) {
				minTabIndex = tabIndex;
			} else if ( tabIndex < minTabIndex ) {
				minTabIndex = tabIndex;
			}
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
	$(this).find( '[tabindex]' ).each( function() {
		var tabIndex = parseInt( $(this).attr( 'tabindex' ), 10 );
		if ( tabIndex > 0 && !isNaN( tabIndex ) ) {
			// Initial value
			if ( maxTabIndex === null ) {
				maxTabIndex = tabIndex;
			} else if ( tabIndex > maxTabIndex ) {
				maxTabIndex = tabIndex;
			}
		}
	} );
	return maxTabIndex;
};
} )( jQuery );
