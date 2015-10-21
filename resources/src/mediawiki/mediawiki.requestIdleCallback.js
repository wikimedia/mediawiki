/*!
 * An interface for scheduling background.
 *
 * Loosely based on https://w3c.github.io/requestidlecallback/
 */
( function ( mw, $ ) {
	var tasks = [],
		maxIdleDuration = 50,
		timeout = null;

	function schedule( trigger ) {
		clearTimeout( timeout );
		timeout = setTimeout( trigger, 700 );
	}

	function triggerIdle() {
		var elapsed,
			start = mw.now();

		while ( tasks.length ) {
			elapsed = mw.now() - start;
			if ( elapsed < maxIdleDuration ) {
				tasks.shift().callback();
			} else {
				// Idle moment expired, try again later
				schedule( triggerIdle );
				break;
			}
		}
	}

	mw.requestIdleCallbackInternal = function ( callback ) {
		var task = { callback: callback };
		tasks.push( task );

		$( function () { schedule( triggerIdle ); } );
	};

	/**
	 * Schedule a deferred task to run in the background.
	 *
	 * @member mw
	 * @param {Function} callback
	 */
	mw.requestIdleCallback = window.requestIdleCallback
		? function ( callback ) {
			window.requestIdleCallback( callback );
		}
		: mw.requestIdleCallbackInternal;
}( mediaWiki, jQuery ) );
