/**
 * Try to catch errors in modules which don't do their own error handling.
 * Based partially on some raven.js (<https://github.com/getsentry/raven-js>) plugins.
 * @class mw.errorLogging
 * @singleton
 */
( function ( mw, $ ) {
	'use strict';

	mw.errorLogging = {
		/**
		 * Fired via mw.track when an error is not handled by local code and is caught by global
		 * error logging.
		 *
		 * @event errorLogging_exception
		 * @param {Error|Mixed} e The error that was thrown. Usually an Error object, but
		 *   in Javascript any value can be used with 'throw' so no guarantees.
		 * @param {string} source Name of the function which threw the error.
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
					mw.track( 'errorLogging.exception', { exception: e, source: name } );
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
		register: function ( window, $ ) {
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

			$.ajax = mw.errorLogging.decorateWithArgsCallback( $.ajax, function ( args ) {
				// can be called as $.ajax( options ) or $.ajax( url, options )
				var options = typeof args[0] === 'object' ? args[0] : args[1];

				// don't die if no options present
				options = options || {};

				$.each( ['complete', 'error', 'success'], function ( _, name ) {
					if ( options[name] ) {
						options[name] = mw.errorLogging.wrap( options[name], '$.ajax.' + name );
					}
				} );
			} );
		}
	};

	mw.errorLogging.register( window, $ );

}( mediaWiki, jQuery ) );
