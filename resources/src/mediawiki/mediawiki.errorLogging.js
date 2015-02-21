/**
 * Try to catch errors in modules which don't do their own error handling.
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
		 * @param {string} errorMessage Error errorMessage.
		 * @param {string} url URL where error was raised.
		 * @param {number} lineNumber Line number where error was raised.
		 * @param {number} [columnNumber] Line number where error was raised. Not all browsers
		 *   support this.
		 * @param {Error|Mixed} [errorObject] The error object. Typically an instance of Error, but anything
		 *   (even a primitive value) passed to a throw clause will end up here.
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
			var id = mw.errorLogging.getId(),
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
		 * @return {string} The error id.
		 */
		getId: function () {
			// We take the easy path and just generate a UUID, this way we don't have to deal
			// with browser and language dependency of the error details. Algorithm is from
			// http://stackoverflow.com/questions/105034/how-to-create-a-guid-uuid-in-javascript/2117523#2117523
			// jshint bitwise: false
			return 'xxxxxxxxxxxx4xxxyxxxxxxxxxxxxxxx'.replace( /[xy]/g, function ( c ) {
				var r = Math.random() * 16 | 0,
					v = c === 'x' ? r : ( r & 0x3 | 0x8 );
				return v.toString( 16 );
			} );
		},

		/**
		 * Install a window.onerror handler that will report via mw.track, while preserving
		 * any previous handler.
		 * @param {Object} window
		 */
		installGlobalHandler: function ( window ) {
			// We will preserve the return value of the previous handler. window.onerror works the
			// opposite way than normal event handlers (returning true will prevent the default
			// action, returning false will let the browser handle the error normally, by e.g.
			// logging to the console), so our fallback old handler needs to return false.
			var oldHandler = window.onerror || function () { return false; };

			/**
			 * Dumb window.onerror handler which forwards the errors via mw.track.
			 * @fires errorLogging_windowOnerror
			 */
			window.onerror = function ( errorMessage, url, lineNumber, columnNumber, errorObject ) {
				var id = mw.errorLogging.getId();

				mw.track( 'errorLogging.windowOnerror', { errorMessage: errorMessage, url: url,
					lineNumber: lineNumber, columnNumber: columnNumber, errorObject: errorObject,
					id: id } );
				return oldHandler.apply( this, arguments );
			};
		}
	};

	mw.errorLogging.installGlobalHandler( window );
}( mediaWiki ) );
