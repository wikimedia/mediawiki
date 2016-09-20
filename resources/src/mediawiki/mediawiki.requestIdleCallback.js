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
	mw.requestIdleCallback = mw.requestIdleCallbackInternal;
	/*
	// XXX: Polyfill disabled due to https://bugs.chromium.org/p/chromium/issues/detail?id=647870
	mw.requestIdleCallback = window.requestIdleCallback
		// Bind because it throws TypeError if context is not window
		? window.requestIdleCallback.bind( window )
		: mw.requestIdleCallbackInternal;
	*/
}( mediaWiki ) );
