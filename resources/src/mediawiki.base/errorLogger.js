/**
 * @class mw.errorLogger
 * @singleton
 */
'use strict';

/**
 * Fired via mw.track when an error is not handled by local code and is caught by the
 * window.onerror handler.
 *
 * @event global_error
 * @param {string} errorMessage Error message.
 * @param {string} url URL where error was raised.
 * @param {number} line Line number where error was raised.
 * @param {number} [column] Line number where error was raised. Not all browsers
 *   support this.
 * @param {Error|Mixed} [errorObject] The error object. Typically an instance of Error, but
 *   anything (even a primitive value) passed to a throw clause will end up here.
 */

/**
 * Fired via mw.track when an error is logged with mw.errorLogger#logError.
 *
 * @event error_caught
 * @param {Error} errorObject The error object
 */

/**
 * Install a `window.onerror` handler that logs errors by notifying both `global.error` and
 * `error.uncaught` topic subscribers that an event has occurred. Note well that the former is
 * done for backwards compatibilty.
 *
 * @private
 * @param {Object} window
 * @fires global_error
 * @fires error_caught
 */
function installGlobalHandler( window ) {
	// We will preserve the return value of the previous handler. window.onerror works the
	// opposite way than normal event handlers (returning true will prevent the default
	// action, returning false will let the browser handle the error normally, by e.g.
	// logging to the console), so our fallback old handler needs to return false.
	var oldHandler = window.onerror || function () {
		return false;
	};

	window.onerror = function ( errorMessage, url, line, column, errorObject ) {
		mw.track( 'global.error', {
			errorMessage: errorMessage,
			url: url,
			lineNumber: line,
			columnNumber: column,
			stackTrace: errorObject ? errorObject.stack : '',
			errorObject: errorObject
		} );

		if ( errorObject ) {
			mw.track( 'error.uncaught', errorObject );
		}

		return oldHandler.apply( this, arguments );
	};
}

mw.errorLogger = {
	/**
	 * Logs an error by notifying subscribers to the given mw.track() topic
	 * (by default `error.caught`) that an event has occurred.
	 *
	 * @param {Error} error
	 * @param {string} [topic='error.caught'] Error topic. Conventionally in the form
	 *   'error.⧼component⧽' (where ⧼component⧽ identifies the code logging the error at a
	 *   high level; e.g. an extension name).
	 * @fires error_caught
	 */
	logError: function ( error, topic ) {
		mw.track( topic || 'error.caught', error );
	}
};

if ( window.QUnit ) {
	mw.errorLogger.installGlobalHandler = installGlobalHandler;
} else {
	installGlobalHandler( window );
}
