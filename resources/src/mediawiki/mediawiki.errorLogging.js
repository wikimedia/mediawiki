/**
 * Try to catch errors in modules which don't do their own error handling.
 * Based partially on some raven.js (<https://github.com/getsentry/raven-js>) plugins.
 * @class mw.errorLogging
 * @singleton
 */
( function ( mw, $ ) {
	'use strict';

	var oldWindowOnerror;

	mw.errorLogging = {
		/**
		 * Fired via mw.track when an error is not handled by local code and is caught by an
		 * automatically added try..catch block.
		 *
		 * @event errorLogging_exception
		 * @param {Error|Mixed} e The error that was thrown. Usually an Error object, but
		 *   in Javascript any value can be used with 'throw' so no guarantees.
		 * @param {string} source Name of the function which threw the error.
		 */

		/**
		 * Fired via mw.track when an error is not handled by local code and is caught by the
		 * window.onerror handler.
		 *
		 * @event errorLogging_windowOnerror
		 * @param {Array} args The arguments with which window.onerror was called. These are brower-
		 *   dependent; https://developer.mozilla.org/en-US/docs/Web/API/GlobalEventHandlers/onerror
		 *   has some documentation.
		 */

		/**
		 * Wrap a function in a try-catch block and report any errors.
		 * @param {Function} fn
		 * @param {string} name Name of the function, for logging
		 * @return {Mixed}
		 */
		wrap: function ( fn, name ) {
			var wrappedFn;

			if ( !$.isFunction( fn ) ) {
				return fn;
			}

			// use named function expression so this is easy to identify in the stack trace
			wrappedFn = function mediawikiErrorLoggingWrapper() {
				try {
					return mw.errorLogging.safeApply( fn, this, arguments );
				} catch ( e ) {
					// Set a flag on the error to avoid re-reporting it when it hits window.onerror.
					// We can't prevent double-reporting error which are not objects this way, but
					// that's a rare enough case that we can just ignore it. We also can't prevent
					// double-reporting in browsers which do not pass the error object to the
					// global error handler, but there is not much we can do about that.
					if ( e !== null && typeof e === 'object' || typeof e === 'function' ) {
						e.mwErrorLoggingProcessed = true;
					}

					mw.track( 'errorLogging.exception', { exception: e, source: name } );

					// Rethrow the exception. This will result in some duplicate reports via
					// window.onerror but it will make sure debug tools of the browser (such as
					// break on error) work nromally.
					throw e;
				}
			};

			// raven.js needs these
			// jscs:disable disallowDanglingUnderscores
			wrappedFn.__raven__ = true;
			wrappedFn.__inner__ = fn;
			// jscs:enable disallowDanglingUnderscores

			return wrappedFn;
		},

		/**
		 * Decorates a function so that it executes a callback on its arguments before
		 * executing the function itself.
		 * @private
		 * @param {Function} fn
		 * @param {Function} callback Callback to invoke when the decorated function is called.
		 * @param {Array} callback.args Arguments with which the decorated function has been called.
		 *  Changes in the arguments will effect the original function.
		 * @return {Function} The decorated function
		 */
		decorateWithArgsCallback: function ( fn, callback ) {
			var _; // http://kangax.github.io/nfe/#safari-bug
			return ( _ = function mediaWikiErrorLoggingDecoratedFunction() {
				var args = [].slice.call( arguments );

				callback( args );

				return mw.errorLogging.safeApply( fn, this, args );
			} );
		},

		/**
		 * Call a function with the given context and args. Works around an IE8 limitation
		 * (Function.apply does not work with certain native functions).
		 * @private
		 * @param {Function} fn
		 * @param {Mixed} context
		 * @param {Array} args
		 * @return {Mixed}
		 */
		safeApply: function ( fn, context, args ) {
			if ( !fn.apply ) {
				// IE8 host object compatibility
				return Function.prototype.apply.call( fn, context, args );
			}
			return fn.apply( context, args );
		},

		/**
		 * Decorate async functions to wrap their callbacks.
		 * @param {Object} window The window object
		 * @param {Object} $ The jQuery object
		 */
		registerAsync: function ( window, $ ) {
			$.each( ['setTimeout', 'setInterval'], function ( _, name ) {
				window[name] = mw.errorLogging.decorateWithArgsCallback( window[name], function ( args ) {
					if ( args[0] ) {
						args[0] = mw.errorLogging.wrap( args[0], name );
					}
				} );
			} );

			$.fn.ready = mw.errorLogging.decorateWithArgsCallback( $.fn.ready, function ( args ) {
				if ( args[0] ) {
					args[0] = mw.errorLogging.wrap( args[0], '$.ready' );
				}
			} );

			$.event.add = mw.errorLogging.decorateWithArgsCallback( $.event.add, function ( args ) {
				var handler, wrappedHandler;

				// $.event.add can be called with a config object instead of a handler
				if ( args[2] && args[2].handler ) {
					handler = args[2].handler;
					wrappedHandler = mw.errorLogging.wrap( handler, '$.event.add' );
					args[2].handler = wrappedHandler;
				} else if ( args[2] ) {
					handler = args[2];
					wrappedHandler = mw.errorLogging.wrap( handler, '$.event.add' );
					args[2] = wrappedHandler;
				}

				// emulate jQuery.proxy() behavior
				wrappedHandler.guid = handler.guid = handler.guid || $.guid++;
			} );
		},

		/**
		 * Dumb window.onerror handler which forwards the errors via mw.track.
		 * @param {Mixed...} args Arguments passed to window.onerror. The number of the arguments
		 *   varies from browser to browser; dealing with that is left to subscribers of this event.
		 * @fires errorLogging_windowOnerror
		 */
		handleWindowOnerror: function ( args ) {
			args = [].slice.call( arguments );

			// Report the error, unless it was already reported via errorLogging.exception
			if ( !args[4] || !args[4].mwErrorLoggingProcessed ) {
				mw.track( 'errorLogging.windowOnerror', { args: args } );
			}

			if ( oldWindowOnerror ) {
				oldWindowOnerror.apply( window, args );
			}

			// Do not return true so the default error handler is invoked and logs to console.
		},

		/**
		 * Register a window.onerror handler, preserving the previous handler
		 * @param {Object} window
		 */
		registerOnerror: function ( window ) {
			oldWindowOnerror = window.onerror;
			window.onerror = mw.errorLogging.handleWindowOnerror;
		},

		/**
		 * Restores the previous window.onerror handler
		 * @param {Object} window
		 */
		unregisterOnerror: function ( window ) {
			window.onerror = oldWindowOnerror;
			oldWindowOnerror = undefined;
		},

		/**
		 * Registers onerror and async error catching if they are enabled via a feature flag.
		 * @param window
		 * @param $
		 */
		register: function ( window, $ ) {
			var registerFlag = mw.config.get( 'wgJavascriptErrorLoggingSamplingRate'),
				samplingRate = parseInt( registerFlag, 10 );

			// Only register if the feature flag is set to true or some integer N; in the latter
			// case, only register for one in every N page load. The extra negation is there to
			// deal with NaN.
			if ( registerFlag !== true && ( !samplingRate || Math.random() > 1 / samplingRate ) ) {
				return;
			}

			mw.errorLogging.registerOnerror( window );
			mw.errorLogging.registerAsync( window, $ );
		}
	};
}( mediaWiki, jQuery ) );
