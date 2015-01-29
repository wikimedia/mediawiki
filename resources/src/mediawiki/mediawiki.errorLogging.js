/**
 * Try to catch errors in modules which don't do their own error handling.
 * Based partially on some raven.js ( https://github.com/getsentry/raven-js ) plugins.
 * @class mw.errorLogging
 * @singleton
 */
( function ( mw, $ ) {
	'use strict';

	mw.errorLogging = {
		/**
		 * Wrap a function in a try-catch block and report any errors.
		 * @param {Function} fn
		 * @param {string} name name of the function, for logging
		 * @return {*}
		 */
		wrap: function ( fn, name ) {
			var wrappedFn;

			if ( !$.isFunction( fn ) ) {
				return fn;
			}

			// use named function expression so this is easy to identify in the stack trace
			wrappedFn = function mediawikiErrorLoggingWrapper() {
				try {
					if ( fn.apply ) {
						return fn.apply( this, arguments );
					} else { // IE8 host object compat
						return Function.prototype.apply.call( fn, this, arguments );
					}
				} catch ( e ) {
					mw.track( 'errorLogging.exception', { exception: e, source: name } );
					throw e ;
				}
			};

			// raven.js needs these
			wrappedFn.__raven__ = true;
			wrappedFn.__inner__ = fn;

			return wrappedFn;
		},

		/**
		 * Decorates a function so that it executes a callback on its arguments before
		 * executing the function itself.
		 * @param {Function} fn
		 * @param {function(Array)} callback a callback which receives the arguments (as a real array).
		 *  Changes in the arguments will effect the original function.
		 */
		decorateWithArgsCallback: function ( fn, callback ) {
			var _; // http://kangax.github.io/nfe/#safari-bug
			return ( _ = function mediaWikiErrorLoggingDecoratedFunction() {
				var args = [].slice.call( arguments );

				callback( args );

				if ( fn.apply ) {
					return fn.apply( this, args );
				} else { // IE8 host object compat
					return Function.prototype.apply.call( fn, this, args );
				}
			} );
		},

		/**
		 * Decorate async functions to wrap their callbacks
		 */
		register: function () {
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

	mw.errorLogging.register();

}( mediaWiki, jQuery ) );
