let doc, HIDDEN, VISIBILITY_CHANGE,
	nextId = 1;
const clearHandles = Object.create( null );

function init( overrideDoc ) {
	doc = overrideDoc || document;

	if ( doc.hidden !== undefined ) {
		HIDDEN = 'hidden';
		VISIBILITY_CHANGE = 'visibilitychange';
	} else if ( doc.mozHidden !== undefined ) {
		HIDDEN = 'mozHidden';
		VISIBILITY_CHANGE = 'mozvisibilitychange';
	} else if ( doc.webkitHidden !== undefined ) {
		HIDDEN = 'webkitHidden';
		VISIBILITY_CHANGE = 'webkitvisibilitychange';
	}
}

init();

/**
 * A library similar to similar to setTimeout and clearTimeout,
 * that pauses the time when page visibility is hidden.
 *
 * @exports mediawiki.visibleTimeout
 * @singleton
 */
module.exports = {
	/**
	 * Generally similar to setTimeout, but pauses the time when page visibility is hidden.
	 *
	 * The provided function is invoked after the page has been cumulatively visible for the
	 * specified number of milliseconds.
	 *
	 * @param {Function} fn Callback
	 * @param {number} delay Time left, in milliseconds.
	 * @return {number} A positive integer value which identifies the timer. This
	 *  value can be passed to clear() to cancel the timeout.
	 */
	set: function ( fn, delay ) {
		let nativeId = null,
			lastStartedAt = mw.now();
		const visibleId = nextId++;

		function clearHandle() {
			if ( nativeId !== null ) {
				clearTimeout( nativeId );
				nativeId = null;
			}
			delete clearHandles[ visibleId ];
			if ( VISIBILITY_CHANGE ) {
				// Circular reference is intentional, chain starts after last definition.
				doc.removeEventListener( VISIBILITY_CHANGE, visibilityCheck, false );
			}
		}

		function onComplete() {
			clearHandle();
			fn();
		}

		function visibilityCheck() {
			const now = mw.now();

			if ( HIDDEN && doc[ HIDDEN ] ) {
				if ( nativeId !== null ) {
					// Calculate how long we were visible, and update the time left.
					delay = Math.max( 0, delay - Math.max( 0, now - lastStartedAt ) );
					if ( delay === 0 ) {
						onComplete();
					} else {
						// Unschedule the native timeout, will restart when visible again.
						clearTimeout( nativeId );
						nativeId = null;
					}
				}
			} else {
				// If we're visible, or if HIDDEN is not supported, then start
				// (or resume) the timeout, which runs the user callback after one
				// delay, unless the page becomes hidden first.
				if ( nativeId === null ) {
					lastStartedAt = now;
					nativeId = setTimeout( onComplete, delay );
				}
			}
		}

		clearHandles[ visibleId ] = clearHandle;
		if ( VISIBILITY_CHANGE ) {
			doc.addEventListener( VISIBILITY_CHANGE, visibilityCheck, false );
		}
		visibilityCheck();

		return visibleId;
	},

	/**
	 * Cancel a visible timeout previously established by calling set.
	 *
	 * Passing an invalid ID silently does nothing.
	 *
	 * @param {number} visibleId The identifier of the visible timeout you
	 *  want to cancel. This ID was returned by the corresponding call to set().
	 */
	clear: function ( visibleId ) {
		if ( visibleId in clearHandles ) {
			clearHandles[ visibleId ]();
		}
	}
};

if ( window.QUnit ) {
	module.exports.init = init;
}
