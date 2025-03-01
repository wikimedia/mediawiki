( function () {
	/**
	 * Prevent the closing of a window with a confirm message (the onbeforeunload event seems to
	 * work in most browsers).
	 *
	 * Provided by the mediawiki.confirmCloseWindow module.
	 *
	 * This supersedes any previous onbeforeunload handler. If there was a handler before, it is
	 * restored when you execute the returned release() function.
	 *
	 * @example
	 * mw.loader.using( 'mediawiki.confirmCloseWindow' ).then(() => {
	 *     var allowCloseWindow = mw.confirmCloseWindow();
	 *     // ... do stuff that can't be interrupted ...
	 *     allowCloseWindow.release();
	 *
	 *    // The second function returned is a trigger function to trigger the check and an alert
	 *    // window manually, e.g.:
	 *
	 *     var allowCloseWindow = mw.confirmCloseWindow();
	 *     // ... do stuff that can't be interrupted ...
	 *     if ( allowCloseWindow.trigger() ) {
	 *         // don't do anything (e.g. destroy the input field)
	 *     } else {
	 *         // do whatever you wanted to do
	 *     }
	 * })
	 * @method confirmCloseWindow
	 * @memberof mw
	 * @param {Object} [options]
	 * @param {string} [options.namespace] Optional jQuery event namespace, to allow loosely coupled
	 *  external code to release your trigger. For example, the VisualEditor extension can use this
	 *  remove the trigger registered by mediawiki.action.edit, without strong runtime coupling.
	 * @param {Function} [options.test]
	 * @param {boolean} [options.test.return=true] Whether to show the dialog to the user.
	 * @return {Object} An object of functions to work with this module
	 */
	mw.confirmCloseWindow = function ( options ) {
		let beforeunloadEvent = 'beforeunload';
		const test = options && options.test || function () {
			return true;
		};

		if ( options && options.namespace ) {
			beforeunloadEvent += '.' + options.namespace;
		}

		/**
		 * @ignore
		 * @param {Event} e
		 * @return {string|undefined}
		 */
		function onBeforeunload( e ) {
			if ( test() ) {
				// Standard supported in Firefox 6+, Chrome 119+, Safari 11.1+
				e.preventDefault();

				// Support: Chrome 1-118, Edge 12-118, Safari 9-11
				//
				// Leave the "extra text" string empty since modern browsers won't display it
				// anyway.
				//
				// Before 2015, the standard behaviour was that when a string is returned here,
				// the browser will prompt a native and localised message like
				// "Are you sure? Unsaved changes may be lost.", with the returned string after
				// it on a new line.
				//
				// As of 2015, this is no longer supported in modern browsers. But, the only
				// cross-browser compatible way to trigger the prompt at all, remains to return
				// a string, any string. The HTML spec says e.preventDefault() is the new way to
				// signal this, but we still support older Chrome/Edge and Safari versions which
				// didn't have it.
				//
				// <https://developer.mozilla.org/en-US/docs/Web/API/Window/beforeunload_event>
				return '';
			}
		}

		$( window ).on( beforeunloadEvent, onBeforeunload );

		return {
			/**
			 * Remove the event listener and don't show an alert anymore if the user wants to leave
			 * the page.
			 *
			 * @ignore
			 */
			release: function () {
				$( window ).off( beforeunloadEvent, onBeforeunload );
			},
			/**
			 * Trigger the module's function manually.
			 *
			 * Check if options.test() returns true and show an alert to the user if they want
			 * to leave this page. Returns false if options.test() returns false or the user
			 * cancelled the alert window (~don't leave the page), true otherwise.
			 *
			 * @ignore
			 * @return {boolean}
			 */
			trigger: function () {
				const message = mw.msg( 'confirmleave-warning' );
				// use confirm to show the message to the user (if options.text() is true)
				// eslint-disable-next-line no-alert
				if ( test() && !confirm( message ) ) {
					// the user want to keep the actual page
					return false;
				}
				// otherwise return true
				return true;
			}
		};
	};
}() );
