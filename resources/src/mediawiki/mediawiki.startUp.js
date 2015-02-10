/**
 * Auto-register from pre-loaded startup scripts
 * @ignore (this line will make JSDuck happy)
 */
( function ( $ ) {
	'use strict';

	if ( $.isFunction( window.startUp ) ) {
		window.startUp();
		window.startUp = undefined;
	}
}( jQuery ) );
