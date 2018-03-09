/*!
 * Event handler for the ellipsis button that expands to show truncated text. 
 * Used in Linker::formatComment().
 */
( function ( $ ) {
	'use strict';

	$( function () {
		$( '.mw-dyntrunc-ellipsis' ).click( function() {
			this.style.display = 'none';
			this.nextSibling.style.display = 'inline';
		} );
	} );
} )( jQuery );

