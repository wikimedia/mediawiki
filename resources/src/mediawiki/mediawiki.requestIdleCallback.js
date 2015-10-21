/*!
 * An interface for scheduling background.
 *
 * Loosely based on https://w3c.github.io/requestidlecallback/
 */
( function ( mw, $ ) {
	var tasks = [],
		// How long (in milliseconds) a single flush should take at most.
		maxIterationDuration = 50,
		timeout = null;

	function runTask( task ) {
		var index = tasks.indexOf( task );
		if ( index === -1 ) {
			// Already run by regular schedule
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

	function schedule( runner ) {
		clearTimeout( timeout );
		timeout = setTimeout( runner, 700 );
	}

	function run() {
		var elapsed,
			start = +new Date(),
			timeRemaining = function () {
				var elapsedNow = +new Date() - start;
				return Math.max( 0, maxIterationDuration - elapsedNow );
			};

		while ( tasks.length ) {
			elapsed = +new Date() - start;
			if ( elapsed < maxIterationDuration ) {
				tasks.shift().callback( {
					timeRemaining: timeRemaining,
					didTimeout: false
				} );
			} else {
				schedule( run );
				break;
			}
		}
	}
	mw.requestIdleCallbackInternal = function ( callback, options ) {
		var task = { callback: callback };
		tasks.push( task );
		$( function () { schedule( run ); } );
		if ( options && options.timeout ) {
			setTimeout( function () {
				runTask( task );
			}, options.timeout );
		}
	};

	/**
	 * Schedule a deferred task to run in the background.
	 *
	 * @param {Function} callback
	 * @param {Object} [options]
	 * @param {number} options.timeout
	 */
	mw.requestIdleCallback = window.requestIdleCallback && window.requestIdleCallback.bind
		? window.requestIdleCallback.bind( window )
		: mw.requestIdleCallbackInternal;
}( mediaWiki, jQuery ) );
