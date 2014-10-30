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
	 * @param {Object} [options]
	 * @param {string} [options.namespace] Namespace for the event registration
	 * @param {string} [options.message]
	 * @param {string} options.message.return The string message to show in the confirm dialog.
	 * @param {Function} [options.test]
	 * @param {boolean} [options.test.return=true] Whether to show the dialog to the user.
	 * @return {Function} Execute this when you want to allow the user to close the window
	 */
	mw.confirmCloseWindow = function ( options ) {
		var savedUnloadHandler,
			mainEventName = 'beforeunload',
			showEventName = 'pageshow';

		options = $.extend( {
			message: mw.message( 'mwe-prevent-close' ).text(),
			test: function () { return true; }
		}, options );

		if ( options.namespace ) {
			mainEventName += '.' + options.namespace;
			showEventName += '.' + options.namespace;
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
				if ( $.isFunction( options.message ) ) {
					return options.message();
				} else {
					return options.message;
				}
			}
		} ).on( showEventName, function () {
			// Re-add onbeforeunload handler
			if ( !window.onbeforeunload && savedUnloadHandler ) {
				window.onbeforeunload = savedUnloadHandler;
			}
		} );

		// return the function they can use to stop this
		return function () {
			$( window ).off( mainEventName + ' ' + showEventName );
		};
	};
} )( mediaWiki, jQuery );
