/*!
 * This file is concatenated to mediawiki.js in debug mode.
 *
 * See Resources.php.
 *
 * @author Michael Dale <mdale@wikimedia.org>
 * @author Trevor Parscal <tparscal@wikimedia.org>
 */
( function () {
	/* global mw */
	/* eslint-disable no-console */
	var original = mw.log;

	// Replace the mw.log() no-op defined in mediawiki.js, with
	// a function that logs to console.log (if available).
	if ( window.console && console.log && console.log.apply ) {
		mw.log = function () {
			console.log.apply( console, arguments );
		};
		// Re-attach original sub methods
		mw.log.warn = original.warn;
		mw.log.error = original.error;
		mw.log.deprecate = original.deprecate;
	}
}() );
