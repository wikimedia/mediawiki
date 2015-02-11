/*!
 * Auto-register from pre-loaded startup scripts
 */
( function ( $ ) {
	'use strict';

	if ( $.isFunction( window.startUp ) ) {
		window.startUp();
		window.startUp = undefined;
	}
}( jQuery ) );
