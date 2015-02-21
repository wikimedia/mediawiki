/**
 * Try to catch errors in modules which don't do their own error handling.
 * Based partially on some raven.js (<https://github.com/getsentry/raven-js>) plugins.
 * @class mw.errorLogging
 * @singleton
 */
( function ( mw ) {
	'use strict';

	mw.errorLogging = {
		/**
		 * Fired via mw.track when an error is not handled by local code and is caught by the
		 * window.onerror handler.
		 *
		 * @event errorLogging_windowOnerror
		 * @param {Array} args The arguments with which window.onerror was called. These are brower-
		 *   dependent; https://developer.mozilla.org/en-US/docs/Web/API/GlobalEventHandlers/onerror
		 *   has some documentation.
		 * @param {string} id Error identifier.
		 */

		/**
		 * Fired via mw.track when an error is caught and reported to mw.errorLogging.
		 *
		 * @event errorLogging_exception
		 * @param {Error|Mixed} e The error that was thrown. Usually an Error object, but
		 *   in Javascript any value can be used with 'throw' so no guarantees.
		 * @param {string} source Name of the function which threw the error.
		 * @param {string} id Error identifier.
		 */

		/**
		 * Report an error and return an id with which it can be referenced.
		 *
		 * Note that error logging is an asynchronous operation on multiple levels, and because
		 * of that returning an error id does not guarantee that the error was successfully logged.
		 *
		 * @param {Error|Mixed} error The error that needs to be logged. Typically an Error object
		 *   that was thrown and caught, but could be a simple string or anything else.
		 * @param {Object} [context] Additional information about the error.
		 * @return {string} An error id.
		 */
		logError: function ( error, context ) {
			var id = mw.errorLogging.getId( error ),
				data = { exception: error, source: 'logError', id: id };

			if ( context ) {
				data.context = context;
			}

			mw.track( 'errorLogging.exception', data );

			return id;
		},

		/**
		 * Generate an error id that can be shown to the user / logged to the console and
		 * used to correlate bug reports with error logs.
		 * @private
		 * @param {Error|Mixed} error An error value, typically an Error object.
		 * @return {string} The error id.
		 */
		getId: function ( error ) {
			// We take the easy path and just generate a UUID, this way we don't have to deal
			// with browser and language dependency of the error details. Algorithm is from
			// http://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid-in-javascript/2117523#2117523
			// jshint unused:false, bitwise: false
			return 'xxxxxxxxxxxx4xxxyxxxxxxxxxxxxxxx'.replace( /[xy]/g, function ( c ) {
				var r = Math.random() * 16 | 0,
					v = c === 'x' ? r : ( r & 0x3 | 0x8 );
				return v.toString( 16 );
			} );
		},

		/**
		 * Dumb window.onerror handler which forwards the errors via mw.track.
		 * @private
		 * @param {Mixed...} args Arguments passed to window.onerror. The number of the arguments
		 *   varies from browser to browser; dealing with that is left to subscribers of this event.
		 * @fires errorLogging_windowOnerror
		 */
		handleWindowOnerror: function ( args ) {
			var error, id;

			args = [].slice.call( arguments );
			error = args[4];
			id = mw.errorLogging.getId( error );

			mw.track( 'errorLogging.windowOnerror', { args: args, id: id } );

			// window.onerror works the opposite way than normal event handlers: returning true
			// will prevent the default action, returning false will let the browser handle the
			// error normally (e.g. log to the console).
			return false;
		},

		/**
		 * Registers the global error handler if it is enabled via a feature flag.
		 * @param window
		 */
		register: function ( window ) {
			var samplingRate = parseInt( mw.config.get( 'wgJavascriptErrorLoggingSamplingRate', 0 ), 10 );

			if ( !samplingRate || Math.random() > 1 / samplingRate ) {
				return;
			}

			window.onerror = mw.errorLogging.handleWindowOnerror;
		}
	};
}( mediaWiki ) );
