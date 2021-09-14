( function () {
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
	 * @param {string} [options.namespace] Optional jQuery event namespace, to allow loosely coupled
	 *  external code to release your trigger. For example, the VisualEditor extension can use this
	 *  remove the trigger registered by mediawiki.action.edit, without strong runtime coupling.
	 * @param {string} [options.message]
	 * @param {string} options.message.return The string message to show in the confirm dialog.
	 * @param {Function} [options.test]
	 * @param {boolean} [options.test.return=true] Whether to show the dialog to the user.
	 * @return {Object} An object of functions to work with this module
	 */
	mw.confirmCloseWindow = function ( options ) {
		var beforeunloadEvent = 'beforeunload',
			message;

		options = $.extend( {
			message: mw.message( 'confirmleave-warning' ).text(),
			test: function () { return true; }
		}, options );

		if ( options.namespace ) {
			beforeunloadEvent += '.' + options.namespace;
		}

		if ( typeof options.message === 'function' ) {
			message = options.message();
		} else {
			message = options.message;
		}

		/**
		 * @ignore
		 * @see <https://developer.mozilla.org/en-US/docs/Web/API/Window/beforeunload_event>
		 * @return {string|undefined}
		 */
		function onBeforeunload() {
			if ( options.test() ) {
				// show an alert with this message
				return message;
			}
		}

		$( window ).on( beforeunloadEvent, onBeforeunload );

		return {
			/**
			 * Remove the event listener and don't show an alert anymore, if the user wants to leave
			 * the page.
			 *
			 * @ignore
			 */
			release: function () {
				$( window ).off( beforeunloadEvent, onBeforeunload );
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
}() );
