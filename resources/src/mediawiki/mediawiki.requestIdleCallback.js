/*!
 * An interface for scheduling background tasks.
 *
 * Loosely based on https://w3c.github.io/requestidlecallback/
 */
( function ( mw ) {
	var maxBusy = 50;

	mw.requestIdleCallbackInternal = function ( callback ) {
		setTimeout( function () {
			var start = mw.now();
			callback( {
				didTimeout: false,
				timeRemaining: function () {
					return Math.max( 0, maxBusy - ( mw.now() - start ) );
				}
			} );
		}, 1 );
	};

	/**
	 * Schedule a deferred task to run in the background.
	 *
	 * @member mw
	 * @param {Function} callback
	 */
	mw.requestIdleCallback = window.requestIdleCallback || mw.requestIdleCallbackInternal;
}( mediaWiki, jQuery ) );
