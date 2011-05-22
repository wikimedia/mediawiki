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
		} else if ( tabIndex < minTabIndex ) {
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
