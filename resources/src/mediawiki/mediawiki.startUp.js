/*!
 * Auto-register from pre-loaded startup scripts
 */
( function ( mw, $ ) {
	'use strict';

	if ( $.isFunction( window.startUp ) ) {
		window.startUp();
		window.startUp = undefined;
		mw.errorLogging.register( window, $ );
	}
}( mediaWiki, jQuery ) );
