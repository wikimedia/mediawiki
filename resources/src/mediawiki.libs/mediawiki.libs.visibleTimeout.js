( function ( mw, undefined ) {
	var hidden, visibilityChange,
		nextVisibleTimeoutId = 0,
		activeTimeouts = {};

	if ( document.hidden !== undefined ) {
		hidden = 'hidden';
		visibilityChange = 'visibilitychange';
	} else if ( document.mozHidden !== undefined ) {
		hidden = 'mozHidden';
		visibilityChange = 'mozvisibilitychange';
	} else if ( document.msHidden !== undefined ) {
		hidden = 'msHidden';
		visibilityChange = 'msvisibilitychange';
	} else if ( document.webkitHidden !== undefined ) {
		hidden = 'webkitHidden';
		visibilityChange = 'webkitvisibilitychange';
	}

	/**
	 * Generally similar to setTimeout, but turns itself on/off on page
	 * visibility changes.
	 *
	 * @param {Function} fn The action to execute after visible timeout has expired.
	 * @param {number} delay The number of ms the page should be visible before
	 *  calling fn.
	 */
	mw.libs.setVisibleTimeout = function ( fn, delay ) {
		var handleVisibilityChange,
			timeoutId = null,
			visibleTimeoutId = nextVisibleTimeoutId++,
			lastStartedAt = new Date().getTime(),
			clearVisibleTimeout = function () {
				if ( timeoutId !== null ) {
					clearTimeout( timeoutId );
					timeoutId = null;
				}
				delete activeTimeouts[visibleTimeoutId];
				if ( hidden !== undefined ) {
					document.removeEventListener( visibilityChange, handleVisibilityChange, false );
				}
			},
			onComplete = function () {
				clearVisibleTimeout();
				fn();
			};

		handleVisibilityChange = function () {
			var now = new Date().getTime();

			if ( document[ hidden ] ) {
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
				// resume timeout if not running
				if ( timeoutId === null ) {
					lastStartedAt = now;
					timeoutId = setTimeout( onComplete, delay );
				}
			}
		};

		activeTimeouts[visibleTimeoutId] = clearVisibleTimeout;
		if ( hidden !== undefined ) {
			document.addEventListener( visibilityChange, handleVisibilityChange, false );
		}
		handleVisibilityChange();

		return visibleTimeoutId;
	};

	/**
	 * Cancel a visible timeout previously established by calling setVisibleTimeout.
	 * Passing an invalid ID silently does nothing.
	 *
	 * @param {number} visibleTimeoutId The identifier of the visible timeout you want
	 *  to cancel. This ID was returned by the corresponding call to setVisibleTimeout().
	 */
	mw.libs.clearVisibleTimeout = function ( visibleTimeoutId ) {
		if ( activeTimeouts.hasOwnProperty( visibleTimeoutId ) ) {
			activeTimeouts[visibleTimeoutId]();
		}
	};
}( mediaWiki ) );
