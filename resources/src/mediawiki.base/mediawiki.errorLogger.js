/**
 * Try to catch errors in modules which don't do their own error handling.
 *
 * @class mw.errorLogger
 * @singleton
 */
mw.errorLogger = {
	// Browser stack trace strings are usually provided on the Error object,
	// and render each stack frame on its own line, e.g.:
	//
	// WebKit browsers:
	//      "at foo (http://w.org/ex.js:11:22)"
	//
	// Gecko browsers:
	//      "foo@http://w.org/ex.js:11:22"
	//
	// The format is not standardized, but the two given above predominate,
	// reflecting the two major browser engine lineages:
	//
	//          WebKit              Gecko
	//          /   \                 |
	//      Safari  Chrome          Firefox
	//              /  |  \
	//             /   |   \
	//      Opera 12+ Edge Brave
	//
	// Given below are regular expressions that extract the "function name" and
	// "location" portions of such strings.
	//
	// For the examples above, a successful match would yield:
	//
	//      [ "foo", "http://w.org/ex.js:11:22" ]
	//
	// This pair can then be re-composed into a new string with whatever format is desired.
	//
	//                begin        end
	//                non-capture  non-capture
	//                group        group
	//                    |         |
	//                   /|\       /|
	regexWebKit: /^\s*at (?:(.*?)\()?(.*?:\d+:\d+)\)?\s*$/i,
	//            - --       --- --   ----------- --- - -
	//           / /         /    |        |       |  |  \___
	// line start /      group 1, |        |       |  |      \
	//           /       function |     group 2,   |  any     line
	//        any # of   name     |   url:line:col |  # of    end
	//        spaces     (maybe   |                |  spaces
	//                    empty)  |                |
	//                            |                |
	//                         literal          literal
	//                           '('              ')'
	//                                        (or nothing)
	//
	//         begin                               end
	//         outer                               outer
	//         non-capture                         non-capture
	//         group                               group
	//             \__    begin        end            |
	//                |   inner        inner          |
	//                |   non-capture  non-capture    |
	//                |   group        group          |
	//                |       |         |  ___________|
	//               /|\     /|\       /| /|
	regexGecko: /^\s*(?:(.*?)(?:\(.*?\))?@)?(.*:\d+:\d+)\s*$/i,
	//           - --    ---    -- - --  -   ----------  - -
	//          /  /      /      | |  \_  \_      |      |_ \__ line
	// line start /   group 1,   | |    |   | group 2,     |    end
	//           /    function   | args |   | url:line:col |
	//      any # of  name       |      |   |              |
	//      spaces    (maybe     |      | literal         any
	//                empty)     |      |  '@'            # of
	//                           |      |                 spaces
	//                        literal  literal
	//                          '('      ')'

	/**
	 * Convert most stack trace strings to a common format.
	 *
	 * If the input string does not match a supported format,
	 * the output will be the empty string.
	 * Otherwise, "at funcName scriptUrl:lineNo:colNo".
	 *
	 * @private
	 * @param {string} str Native stack trace string
	 * @return {string} Cross-browser normal stack trace
	 */
	crossBrowserStackTrace: function ( str ) {
		var result = '',
			lines = str.split( '\n' ),
			parts,
			i;

		for ( i = 0; i < lines.length; i++ ) {
			// Try to boil each line of the stack trace string down to a function and
			// location pair, e.g. [ 'myFoo', 'myscript.js:1:23' ].
			// using regexes that match the WebKit-like and Gecko-like stack trace
			// formats, in that order.
			//
			// A line will match only one of the two expressions (or neither).
			// Note that in JavaScript regex, the first value in the array is
			// the original string.
			parts = mw.errorLogger.regexWebKit.exec( lines[ i ] ) ||
				mw.errorLogger.regexGecko.exec( lines[ i ] );

			if ( parts ) {
				// If the line was successfully matched into two parts, then re-assemble
				// the parts in our output format.
				if ( parts[ 1 ] ) {
					result += 'at ' + parts[ 1 ] + ' ' + parts[ 2 ];
				} else {
					result += 'at ' + parts[ 2 ];
				}
				if ( i < lines.length ) {
					result += '\n';
				}
			}
		}

		return result;
	},

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

			// The 'stack' property is non-standard, so we check.
			// In some browsers it will be undefined, and in some
			// it may be an object, etc.
			var stack = '';

			if ( errorObject && typeof errorObject.stack === 'string' ) {
				stack = mw.errorLogger.crossBrowserStackTrace( errorObject.stack );
			}

			mw.track( 'global.error', {
				errorMessage: errorMessage,
				url: url,
				lineNumber: lineNumber,
				columnNumber: columnNumber,
				stackTrace: stack,
				errorObject: errorObject
			} );
			return oldHandler.apply( this, arguments );
		};
	}
};

mw.errorLogger.installGlobalHandler( window );
