/**
 * Try to catch errors in modules which don't do their own error handling.
 *
 * @class mw.errorLogger
 * @singleton
 */
( function () {
	'use strict';

	mw.errorLogger = {
		/**
		 * Fired via mw.track when an error is not handled by local code and is caught by the
		 * window.onerror handler.
		 *
		 * @event global_error
		 * @param {string} errorMessage Error errorMessage.
		 * @param {string} url URL where error was raised.
		 * @param {number} lineNumber Line number where error was raised.
		 * @param {number} [columnNumber] Line number where error was raised. Not all browsers
		 *   support this.
		 * @param {Error|Mixed} [errorObject] The error object. Typically an instance of Error, but anything
		 *   (even a primitive value) passed to a throw clause will end up here.
		 */

		/**
		 * Install a window.onerror handler that will report via mw.track, while preserving
		 * any previous handler.
		 *
		 * @param {Object} window
		 */
		installGlobalHandler: function ( window ) {
			// We will preserve the return value of the previous handler. window.onerror works the
			// opposite way than normal event handlers (returning true will prevent the default
			// action, returning false will let the browser handle the error normally, by e.g.
			// logging to the console), so our fallback old handler needs to return false.
			var oldHandler = window.onerror || function () {
				return false;
			};

			/**
			 * Dumb window.onerror handler which forwards the errors via mw.track.
			 *
			 * @param {string} errorMessage
			 * @param {string} url
			 * @param {number} lineNumber
			 * @param {number} [columnNumber]
			 * @param {Error|Mixed} [errorObject]
			 * @return {boolean} True to prevent the default action
			 * @fires global_error
			 */
			window.onerror = function ( errorMessage, url, lineNumber, columnNumber, errorObject ) {
				mw.track( 'global.error', { errorMessage: errorMessage, url: url,
					lineNumber: lineNumber, columnNumber: columnNumber, errorObject: errorObject } );
				return oldHandler.apply( this, arguments );
			};
		}
	};

	mw.errorLogger.installGlobalHandler( window );
}() );
