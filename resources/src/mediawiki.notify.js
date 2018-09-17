/**
 * @class mw.plugin.notify
 */
( function () {
	'use strict';

	/**
	 * @see mw.notification#notify
	 * @see mw.notification#defaults
	 * @param {HTMLElement|HTMLElement[]|jQuery|mw.Message|string} message
	 * @param {Object} options See mw.notification#defaults for details.
	 * @return {jQuery.Promise}
	 */
	mw.notify = function ( message, options ) {
		// Don't bother loading the whole notification system if we never use it.
		return mw.loader.using( 'mediawiki.notification' )
			.then( function () {
				// Call notify with the notification the user requested of us.
				return mw.notification.notify( message, options );
			} );
	};

	/**
	 * @class mw
	 * @mixins mw.plugin.notify
	 */

}() );
