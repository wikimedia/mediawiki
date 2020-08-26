( function () {
	var hidden, visibilityChange,
		nextVisibleTimeoutId = 1,
		activeTimeouts = {},
		document = window.document,
		init = function ( overrideDoc ) {
			if ( overrideDoc !== undefined ) {
				document = overrideDoc;
			}

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
		};

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
			var handleVisibilityChange,
				timeoutId = null,
				visibleTimeoutId = nextVisibleTimeoutId++,
				lastStartedAt = mw.now(),
				clearVisibleTimeout = function () {
					if ( timeoutId !== null ) {
						clearTimeout( timeoutId );
						timeoutId = null;
					}
					delete activeTimeouts[ visibleTimeoutId ];
					if ( hidden !== undefined ) {
						document.removeEventListener( visibilityChange, handleVisibilityChange, false );
					}
				},
				onComplete = function () {
					clearVisibleTimeout();
					fn();
				};

			handleVisibilityChange = function () {
				var now = mw.now();

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

			activeTimeouts[ visibleTimeoutId ] = clearVisibleTimeout;
			if ( hidden !== undefined ) {
				document.addEventListener( visibilityChange, handleVisibilityChange, false );
			}
			handleVisibilityChange();

			return visibleTimeoutId;
		},

		/**
		 * Cancel a visible timeout previously established by calling set.
		 * Passing an invalid ID silently does nothing.
		 *
		 * @param {number} visibleTimeoutId The identifier of the visible
		 *  timeout you want to cancel. This ID was returned by the
		 *  corresponding call to set().
		 */
		clear: function ( visibleTimeoutId ) {
			if ( Object.prototype.hasOwnProperty.call( activeTimeouts, visibleTimeoutId ) ) {
				activeTimeouts[ visibleTimeoutId ]();
			}
		}
	};

	if ( window.QUnit ) {
		module.exports.setDocument = init;
	}

}() );
