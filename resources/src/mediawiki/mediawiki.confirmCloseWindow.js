( function ( mw, $ ) {
	/**
	 * @method confirmCloseWindow
	 * @member mw
	 *
	 * Prevent the closing of a window with a confirm message (the onbeforeunload event seems to
	 * work in most browsers.)
	 *
	 * This supersedes any previous onbeforeunload handler. If there was a handler before, it is
	 * restored when you execute the returned function.
	 *
	 *     var allowCloseWindow = mw.confirmCloseWindow();
	 *     // ... do stuff that can't be interrupted ...
	 *     allowCloseWindow();
	 *
	 * You can define one additional event name, which, when triggered on the window object, also shows a confirm
	 * window to the user, if your test function returns true. The confirmation window looks slightly different
	 * from the one created by onbeforeunload event, but has the same message.
	 * Usage example:
	 *
	 *		var allowCloseWindow = mw.confirmCloseWindow( {
	 *			test: function () {
	 *				return true; // example
	 *			},
	 *			closeEventName: 'customCloseEvent'
	 *		} );
	 *		// .. other things
	 *		// will be true, if the test function returns false or if the user confirms the warning window
	 *		var canClosed = $( window ).triggerHandler( 'customCloseEvent' );
	 *
	 * @param {Object} [options]
	 * @param {string} [options.namespace] Namespace for the event registration
	 * @param {string} [options.message]
	 * @param {string} options.message.return The string message to show in the confirm dialog.
	 * @param {Function} [options.test]
	 * @param {String} [options.closeEventName] Name of one additional event name
	 * @param {boolean} [options.test.return=true] Whether to show the dialog to the user.
	 * @return {Function} Execute this when you want to allow the user to close the window
	 */
	mw.confirmCloseWindow = function ( options ) {
		var savedUnloadHandler,
			mainEventName = 'beforeunload',
			showEventName = 'pageshow',
			closeEventName = options.closeEventName || '',
			message;

		options = $.extend( {
			message: mw.message( 'mwe-prevent-close' ).text(),
			test: function () { return true; }
		}, options );

		if ( options.namespace ) {
			mainEventName += '.' + options.namespace;
			showEventName += '.' + options.namespace;
			if ( closeEventName ) {
				closeEventName += '.' + options.namespace;
			}
		}

		if ( $.isFunction( options.message ) ) {
			message = options.message();
		} else {
			message = options.message;
		}

		$( window ).on( mainEventName, function () {
			if ( options.test() ) {
				// remove the handler while the alert is showing - otherwise breaks caching in Firefox (3?).
				// but if they continue working on this page, immediately re-register this handler
				savedUnloadHandler = window.onbeforeunload;
				window.onbeforeunload = null;
				setTimeout( function () {
					window.onbeforeunload = savedUnloadHandler;
				}, 1 );

				// show an alert with this message
				return message;
			}
		} ).on( showEventName, function () {
			// Re-add onbeforeunload handler
			if ( !window.onbeforeunload && savedUnloadHandler ) {
				window.onbeforeunload = savedUnloadHandler;
			}
		} ).on( closeEventName, function () {
			// use window.confirm to show the message to the user (if options.text() is true)
			if ( options.test() && !this.confirm( message ) ) {
				// the user want to keep the actual page
				return false;
			}
			// otherwise return true
			return true;
		} );

		// return the function they can use to stop this
		return function () {
			$( window ).off( mainEventName + ' ' + showEventName + ' ' + closeEventName );
		};
	};
} )( mediaWiki, jQuery );
