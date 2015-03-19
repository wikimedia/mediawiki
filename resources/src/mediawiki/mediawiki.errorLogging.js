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
			var registerFlag = mw.config.get( 'wgJavascriptErrorLoggingSamplingRate'),
				samplingRate = parseInt( registerFlag, 10 );

			// Only register if the feature flag is set to true or some integer N; in the latter
			// case, only register for one in every N page load. The extra negation is there to
			// deal with NaN.
			if ( registerFlag !== true && ( !samplingRate || Math.random() > 1 / samplingRate ) ) {
				return;
			}

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

			// There is no easy way to access user callbacks in $.ajax as it uses a promise
			// mechanism; instead we replace the XHR object with a proxy and use that to wrap the
			// internal jQuery callback that handles the onreadystatechange event. This will miss
			// some things such as JSONP requests, but still get most things.
			$.ajax = mw.errorLogging.decorateWithArgsCallback( $.ajax, function ( args ) {
				var options, finalOptions;

				if ( typeof args[0] === 'object' ) {
					options = args[0] || ( args[0] = {} );
				} else {
					options = args[1] || ( args[1] = {} );
				}
				finalOptions = $.ajaxSetup( {}, options );

				options.xhr = function mediaWikiErrorLoggingDecoratedXhrFactory() {
					var proxyXhr = {},
						realXhr = finalOptions.xhr.call( this ),
						xhrProperties = ['readyState', 'status', 'responseText', 'statusText'].concat( finalOptions.xhrFields || [] ),
						xhrMethods = ['open', 'overrideMimeType', 'setRequestHeader', 'getAllResponseHeaders', 'send', 'abort'];

					try { // IE 8 throws if defineProperty is used on non-DOM objects
						$.each( xhrProperties, function ( _, prop ) {
							Object.defineProperty( proxyXhr, prop, {
								enumerable: true,
								configurable: true,
								get: function () {
									return realXhr[prop];
								},
								set: function ( val ) {
									realXhr[prop] = val;
								}
							} );
						} );

						$.each( xhrMethods, function ( _, method ) {
							if ( realXhr[method] ) {
								proxyXhr[method] = function () {
									var context = this === proxyXhr ? realXhr : this;
									return mw.errorLogging.safeApply( realXhr[method], context, arguments );
								};
							}
						} );

						Object.defineProperty( proxyXhr, 'onreadystatechange', {
							enumerable: true,
							configurable: true,
							get: function () {
								return realXhr.onreadystatechange;
							},
							set: function ( val ) {
								realXhr.onreadystatechange = mw.errorLogging.wrap( val, 'xhr.onreadystatechange' );
							}
						} );

						return proxyXhr;
					} catch ( e ) {
						return realXhr;
					}
				};
			} );
		}
	};
}( mediaWiki, jQuery ) );
