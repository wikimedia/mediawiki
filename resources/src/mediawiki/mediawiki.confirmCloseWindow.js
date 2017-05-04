( function ( mw, $ ) {
	/**
	 * Prevent the closing of a window with a confirm message (the onbeforeunload event seems to
	 * work in most browsers.)
	 *
	 * This supersedes any previous onbeforeunload handler. If there was a handler before, it is
	 * restored when you execute the returned release() function.
	 *
	 *     var allowCloseWindow = mw.confirmCloseWindow();
	 *     // ... do stuff that can't be interrupted ...
	 *     allowCloseWindow.release();
	 *
	 * The second function returned is a trigger function to trigger the check and an alert
	 * window manually, e.g.:
	 *
	 *     var allowCloseWindow = mw.confirmCloseWindow();
	 *     // ... do stuff that can't be interrupted ...
	 *     if ( allowCloseWindow.trigger() ) {
	 *         // don't do anything (e.g. destroy the input field)
	 *     } else {
	 *         // do whatever you wanted to do
	 *     }
	 *
	 * @method confirmCloseWindow
	 * @member mw
	 * @param {Object} [options]
	 * @param {string} [options.namespace] Namespace for the event registration
	 * @param {string} [options.message]
	 * @param {string} options.message.return The string message to show in the confirm dialog.
	 * @param {Function} [options.test]
	 * @param {boolean} [options.test.return=true] Whether to show the dialog to the user.
	 * @return {Object} An object of functions to work with this module
	 */
	mw.confirmCloseWindow = function ( options ) {
		var savedUnloadHandler,
			mainEventName = 'beforeunload',
			showEventName = 'pageshow',
			message;

		options = $.extend( {
			message: mw.message( 'mwe-prevent-close' ).text(),
			test: function () { return true; }
		}, options );

		if ( options.namespace ) {
			mainEventName += '.' + options.namespace;
			showEventName += '.' + options.namespace;
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
		} );

		/**
		 * Return the object with functions to release and manually trigger the confirm alert
		 *
		 * @ignore
		 */
		return {
			/**
			 * Remove all event listeners and don't show an alert anymore, if the user wants to leave
			 * the page.
			 *
			 * @ignore
			 */
			release: function () {
				$( window ).off( mainEventName + ' ' + showEventName );
			},
			/**
			 * Trigger the module's function manually: Check, if options.test() returns true and show
			 * an alert to the user if he/she want to leave this page. Returns false, if options.test() returns
			 * false or the user cancelled the alert window (~don't leave the page), true otherwise.
			 *
			 * @ignore
			 * @return {boolean}
			 */
			trigger: function () {
				// use confirm to show the message to the user (if options.text() is true)
				// eslint-disable-next-line no-alert
				if ( options.test() && !confirm( message ) ) {
					// the user want to keep the actual page
					return false;
				}
				// otherwise return true
				return true;
			}
		};
	};
}( mediaWiki, jQuery ) );
