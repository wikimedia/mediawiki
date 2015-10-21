/*!
 * An interface for scheduling background.
 *
 * Loosely based on https://w3c.github.io/requestidlecallback/
 */
( function ( mw, $ ) {
	var tasks = [],
		maxIdleDuration = 50,
		timeout = null;

	function runTask( task ) {
		var index = tasks.indexOf( task );
		if ( index === -1 ) {
			// Already run by triggerIdle
			return;
		}
		tasks.splice( index, 1 );
		task.callback( {
			didTimeout: true,
			timeRemaining: function () {
				return 0;
			}
		} );
	}

	function schedule( trigger ) {
		clearTimeout( timeout );
		timeout = setTimeout( trigger, 700 );
	}

	function triggerIdle() {
		var elapsed,
			start = mw.now(),
			timeRemaining = function () {
				var elapsedNow = mw.now() - start;
				return Math.max( 0, maxIdleDuration - elapsedNow );
			};

		while ( tasks.length ) {
			elapsed = mw.now() - start;
			if ( elapsed < maxIdleDuration ) {
				tasks.shift().callback( {
					timeRemaining: timeRemaining,
					didTimeout: false
				} );
			} else {
				// Idle moment expired, try again later
				schedule( triggerIdle );
				break;
			}
		}
	}

	mw.requestIdleCallbackInternal = function ( callback, options ) {
		var task = { callback: callback };
		tasks.push( task );

		$( function () { schedule( triggerIdle ); } );

		if ( options && options.timeout ) {
			setTimeout( function () {
				runTask( task );
			}, options.timeout );
		}
	};

	/**
	 * Schedule a deferred task to run in the background.
	 *
	 * @member mw
	 * @param {Function} callback
	 * @param {Object} [options]
	 * @param {number} options.timeout Maximum delay (estimate)
	 */
	mw.requestIdleCallback = window.requestIdleCallback && window.requestIdleCallback.bind
		? window.requestIdleCallback.bind( window )
		: mw.requestIdleCallbackInternal;
}( mediaWiki, jQuery ) );
