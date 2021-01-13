( function () {
	var doc, HIDDEN, VISIBILITY_CHANGE,
		nextVisibleTimeoutId = 1,
		activeTimeouts = Object.create( null );

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
	 * @class mw.visibleTimeout
	 * @singleton
	 */
	module.exports = {
		/**
		 * Generally similar to setTimeout, but turns itself on/off on page
		 * visibility changes. The passed function fires after the page has been
		 * cumulatively visible for the specified number of ms.
		 *
		 * @param {Function} fn The action to execute after visible timeout has expired.
		 * @param {number} delay The number of ms the page should be visible before
		 *  calling fn.
		 * @return {number} A positive integer value which identifies the timer. This
		 *  value can be passed to clearVisibleTimeout() to cancel the timeout.
		 */
		set: function ( fn, delay ) {
			var timeoutId = null,
				visibleTimeoutId = nextVisibleTimeoutId++,
				lastStartedAt = mw.now();

			function clearVisibleTimeout() {
				if ( timeoutId !== null ) {
					clearTimeout( timeoutId );
					timeoutId = null;
				}
				delete activeTimeouts[ visibleTimeoutId ];
				if ( VISIBILITY_CHANGE ) {
					// Circular reference is intentional, chain starts after last definition.
					// eslint-disable-next-line no-use-before-define
					doc.removeEventListener( VISIBILITY_CHANGE, handleVisibilityChange, false );
				}
			}

			function onComplete() {
				clearVisibleTimeout();
				fn();
			}

			function handleVisibilityChange() {
				var now = mw.now();

				if ( HIDDEN && doc[ HIDDEN ] ) {
					// pause timeout if running
					if ( timeoutId !== null ) {
						delay = Math.max( 0, delay - Math.max( 0, now - lastStartedAt ) );
						if ( delay === 0 ) {
							onComplete();
						} else {
							clearTimeout( timeoutId );
							timeoutId = null;
						}
					}
				} else {
					// If we're visible, or if HIDDEN is not supported, then start
					// (or resume) the timeout, which runs the user callback after one
					// delay, unless the page becomes hidden first.
					if ( timeoutId === null ) {
						lastStartedAt = now;
						timeoutId = setTimeout( onComplete, delay );
					}
				}
			}

			activeTimeouts[ visibleTimeoutId ] = clearVisibleTimeout;
			if ( VISIBILITY_CHANGE ) {
				doc.addEventListener( VISIBILITY_CHANGE, handleVisibilityChange, false );
			}
			handleVisibilityChange();

			return visibleTimeoutId;
		},

		/**
		 * Cancel a visible timeout previously established by calling set.
		 *
		 * Passing an invalid ID silently does nothing.
		 *
		 * @param {number} visibleTimeoutId The identifier of the visible timeout you
		 *  want to cancel. This ID was returned by the corresponding call to set().
		 */
		clear: function ( visibleTimeoutId ) {
			if ( visibleTimeoutId in activeTimeouts ) {
				activeTimeouts[ visibleTimeoutId ]();
			}
		}
	};

	if ( window.QUnit ) {
		module.exports.init = init;
	}

}() );
