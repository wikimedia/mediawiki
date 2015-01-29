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
		 * Fired via mw.track when an error is not handled by local code and is caught by the
		 * window.onerror handler.
		 *
		 * @event errorLogging_windowOnerror
		 * @param {Array} args The arguments with which window.onerror was called. These are brower-
		 *   dependent; https://developer.mozilla.org/en-US/docs/Web/API/GlobalEventHandlers/onerror
		 *   has some documentation.
		 */

		/**
		 * Dumb window.onerror handler which forwards the errors via mw.track.
		 * Other error reporting logic can set the `mwErrorLoggingProcessed` property of an error
		 * object to suppress reporting via window.onerror (which might or might not work,
		 * depending on whether the browser passes the exception object to window.onerror).
		 *
		 * @param {Mixed...} args Arguments passed to window.onerror. The number of the arguments
		 *   varies from browser to browser; dealing with that is left to subscribers of this event.
		 * @fires errorLogging_windowOnerror
		 */
		handleWindowOnerror: function ( args ) {
			args = [].slice.call( arguments );

			// Report the error, unless it was already reported by some other means
			if ( !args[4] || !args[4].mwErrorLoggingProcessed ) {
				mw.track( 'errorLogging.windowOnerror', { args: args } );
			}

			if ( oldWindowOnerror ) {
				oldWindowOnerror.apply( window, args );
			}

			// window.onerror works the opposite way than normal event handlers: returning true
			// will prevent the default action, returning false will let the browser handle the
			// error normally (e.g. log to the console).
			return false;
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
		 * Registers the global error handler if it is enabled via a feature flag.
		 * @param window
		 */
		register: function ( window ) {
			var samplingRate = parseInt( mw.config.get( 'wgJavascriptErrorLoggingSamplingRate', 0 ) );

			if ( !samplingRate || Math.random() > 1 / samplingRate ) {
				return;
			}

			mw.errorLogging.registerOnerror( window );
		}
	};
}( mediaWiki, jQuery ) );
