/**
 * @class mw.plugin.notify
 */
( function ( mw ) {
	'use strict';

	/**
	 * @see mw.notification#notify
	 * @param message
	 * @param options
	 */
	mw.notify = function ( message, options ) {
		// Don't bother loading the whole notification system if we never use it.
		mw.loader.using( 'mediawiki.notification', function () {
			// Don't bother calling mw.loader.using a second time after we've already loaded mw.notification.
			mw.notify = mw.notification.notify;
			// Call notify with the notification the user requested of us.
			mw.notify( message, options );
		} );
	};

	/**
	 * @class mw
	 * @mixins mw.plugin.notify
	 */

}( mediaWiki ) );
